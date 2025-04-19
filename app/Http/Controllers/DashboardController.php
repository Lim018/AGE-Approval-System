<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [];
        
        if ($user->isPegawai()) {
            $stats = [
                'total' => $user->pengajuans()->count(),
                'pending' => $user->pengajuans()->whereIn('status', ['pending_atasan', 'pending_kadep', 'pending_hrd'])->count(),
                'approved' => $user->pengajuans()->where('status', 'approved')->count(),
                'rejected' => $user->pengajuans()->whereIn('status', ['rejected_atasan', 'rejected_kadep', 'rejected'])->count(),
            ];
        } else if ($user->isAtasan()) {
            $pegawaiIds = $user->subordinates->pluck('id');
            $stats = [
                'total' => Pengajuan::whereIn('user_id', $pegawaiIds)->count(),
                'pending' => Pengajuan::whereIn('user_id', $pegawaiIds)->where('status', 'pending_atasan')->count(),
                'approved' => Pengajuan::whereIn('user_id', $pegawaiIds)
                    ->whereHas('approvalHistories', function($query) use ($user) {
                        $query->where('approved_by', $user->id)->where('status', 'approved');
                    })->count(),
                'rejected' => Pengajuan::whereIn('user_id', $pegawaiIds)
                    ->whereHas('approvalHistories', function($query) use ($user) {
                        $query->where('approved_by', $user->id)->where('status', 'rejected');
                    })->count(),
            ];
        } else if ($user->isKepalaDepartemen()) {
            $stats = [
                'total' => Pengajuan::whereHas('user', function($query) use ($user) {
                    $query->where('department_id', $user->department_id);
                })->count(),
                'pending' => Pengajuan::whereHas('user', function($query) use ($user) {
                    $query->where('department_id', $user->department_id);
                })->where('status', 'pending_kadep')->count(),
                'approved' => Pengajuan::whereHas('approvalHistories', function($query) use ($user) {
                    $query->where('approved_by', $user->id)->where('status', 'approved');
                })->count(),
                'rejected' => Pengajuan::whereHas('approvalHistories', function($query) use ($user) {
                    $query->where('approved_by', $user->id)->where('status', 'rejected');
                })->count(),
            ];
        } else if ($user->isHRD()) {
            $stats = [
                'total' => Pengajuan::count(),
                'pending' => Pengajuan::where('status', 'pending_hrd')->count(),
                'approved' => Pengajuan::where('status', 'approved')->count(),
                'rejected' => Pengajuan::whereIn('status', ['rejected_atasan', 'rejected_kadep', 'rejected'])->count(),
            ];
        }
        
        $recentPengajuans = $user->isPegawai() 
            ? $user->pengajuans()->latest()->take(5)->get()
            : Pengajuan::latest()->take(5)->get();
            
        $unreadNotificationsCount = $user->notifications()->where('dibaca', false)->count();
        
        return view('dashboard.index', compact('stats', 'recentPengajuans', 'unreadNotificationsCount'));
    }
}