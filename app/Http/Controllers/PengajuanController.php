<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Notification;
use App\Models\ApprovalHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isPegawai()) {
            $pengajuans = $user->pengajuans;
        } else if ($user->isAtasan()) {
            $pegawaiIds = $user->subordinates->pluck('id');
            $pengajuans = Pengajuan::whereIn('user_id', $pegawaiIds)
                ->where('status', 'pending_atasan')
                ->get();
        } else if ($user->isKepalaDepartemen()) {
            $pengajuans = Pengajuan::whereHas('user', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })->where('status', 'pending_kadep')->get();
        } else if ($user->isHRD()) {
            $pengajuans = Pengajuan::where('status', 'pending_hrd')->get();
        }
        
        return view('pengajuan.index', compact('pengajuans'));
    }
    
    public function create()
    {
        return view('pengajuan.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'alasan' => 'required|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
        
        $data = $request->except('dokumen_pendukung');
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending_atasan';
        
        if ($request->hasFile('dokumen_pendukung')) {
            $path = $request->file('dokumen_pendukung')->store('dokumen_pendukung');
            $data['dokumen_pendukung'] = $path;
        }
        
        $pengajuan = Pengajuan::create($data);
        
        // Kirim notifikasi ke atasan
        $atasan = Auth::user()->supervisor;
        if ($atasan) {
            Notification::create([
                'user_id' => $atasan->id,
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Pengajuan Baru',
                'pesan' => 'Ada pengajuan baru dari ' . Auth::user()->name . ' yang memerlukan persetujuan Anda.',
            ]);
        }
        
        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil dibuat dan menunggu persetujuan atasan.');
    }
    
    public function show(Pengajuan $pengajuan)
    {
        $approvalHistories = $pengajuan->approvalHistories()
            ->with('approver')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('pengajuan.show', compact('pengajuan', 'approvalHistories'));
    }
    
    public function approve(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();
        $request->validate([
            'komentar' => 'nullable|string',
        ]);
        
        $level = $pengajuan->getCurrentApprovalLevel();
        
        if (!$level) {
            return back()->with('error', 'Status pengajuan tidak valid.');
        }
        
        if (($level === 'atasan' && !$user->isAtasan()) || 
            ($level === 'kadep' && !$user->isKepalaDepartemen()) || 
            ($level === 'hrd' && !$user->isHRD())) {
            return back()->with('error', 'Anda tidak memiliki hak untuk menyetujui pengajuan ini.');
        }
        
        // Simpan riwayat persetujuan
        ApprovalHistory::create([
            'pengajuan_id' => $pengajuan->id,
            'approved_by' => $user->id,
            'level' => $level,
            'status' => 'approved',
            'komentar' => $request->komentar,
        ]);
        
        // Update status pengajuan
        if ($level === 'atasan') {
            $pengajuan->update(['status' => 'pending_kadep']);
            
            // Notifikasi ke Kepala Departemen
            $kepala = $pengajuan->user->department->head;
            if ($kepala) {
                Notification::create([
                    'user_id' => $kepala->id,
                    'pengajuan_id' => $pengajuan->id,
                    'judul' => 'Pengajuan Memerlukan Persetujuan',
                    'pesan' => 'Pengajuan dari ' . $pengajuan->user->name . ' telah disetujui oleh atasan dan memerlukan persetujuan Anda.',
                ]);
            }
        } else if ($level === 'kadep') {
            $pengajuan->update(['status' => 'pending_hrd']);
            
            // Notifikasi ke HRD
            $hrds = User::where('role', 'hrd')->get();
            foreach ($hrds as $hrd) {
                Notification::create([
                    'user_id' => $hrd->id,
                    'pengajuan_id' => $pengajuan->id,
                    'judul' => 'Pengajuan Memerlukan Persetujuan Final',
                    'pesan' => 'Pengajuan dari ' . $pengajuan->user->name . ' telah disetujui oleh Kepala Departemen dan memerlukan persetujuan final Anda.',
                ]);
            }
        } else if ($level === 'hrd') {
            $pengajuan->update(['status' => 'approved']);
        }
        
        // Notifikasi ke pegawai
        Notification::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->id,
            'judul' => 'Pengajuan Disetujui',
            'pesan' => 'Pengajuan Anda telah disetujui oleh ' . ucfirst($level) . ': ' . $user->name,
        ]);
        
        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil disetujui.');
    }
    
    public function reject(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();
        $request->validate([
            'komentar' => 'required|string',
        ]);
        
        $level = $pengajuan->getCurrentApprovalLevel();
        
        if (!$level) {
            return back()->with('error', 'Status pengajuan tidak valid.');
        }
        
        if (($level === 'atasan' && !$user->isAtasan()) || 
            ($level === 'kadep' && !$user->isKepalaDepartemen()) || 
            ($level === 'hrd' && !$user->isHRD())) {
            return back()->with('error', 'Anda tidak memiliki hak untuk menolak pengajuan ini.');
        }
        
        // Simpan riwayat penolakan
        ApprovalHistory::create([
            'pengajuan_id' => $pengajuan->id,
            'approved_by' => $user->id,
            'level' => $level,
            'status' => 'rejected',
            'komentar' => $request->komentar,
        ]);
        
        // Update status pengajuan
        if ($level === 'atasan') {
            $pengajuan->update(['status' => 'rejected_atasan']);
        } else if ($level === 'kadep') {
            $pengajuan->update(['status' => 'rejected_kadep']);
        } else if ($level === 'hrd') {
            $pengajuan->update(['status' => 'rejected']);
        }
        
        // Notifikasi ke pegawai
        Notification::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->id,
            'judul' => 'Pengajuan Ditolak',
            'pesan' => 'Pengajuan Anda ditolak oleh ' . ucfirst($level) . ': ' . $user->name . ' dengan alasan: ' . $request->komentar,
        ]);
        
        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }
}