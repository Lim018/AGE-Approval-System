<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pengajuan;
use App\Models\Notification;
use App\Models\ApprovalHistory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'pending_atasan',
            'pending_kadep',
            'pending_hrd',
            'approved',
            'rejected_atasan',
            'rejected_kadep',
            'rejected'
        ];

        $pegawaiUsers = User::where('role', 'pegawai')->get();

        foreach ($pegawaiUsers as $index => $user) {
            // Create 2 pengajuan for each employee
            for ($i = 1; $i <= 2; $i++) {
                $startDate = Carbon::now()->addDays(rand(5, 30));
                $endDate = (clone $startDate)->addDays(rand(1, 7));
                
                // Randomly select a status
                $status = $statuses[array_rand($statuses)];
                
                $pengajuan = Pengajuan::create([
                    'user_id' => $user->id,
                    'judul_kegiatan' => 'Kegiatan ' . $i . ' ' . $user->name,
                    'alasan' => 'Alasan untuk kegiatan ' . $i . ' oleh ' . $user->name,
                    'waktu_mulai' => $startDate,
                    'waktu_selesai' => $endDate,
                    'lokasi' => 'Lokasi ' . $i,
                    'status' => $status,
                ]);
                
                // Create notifications based on status
                $this->createNotifications($pengajuan, $user, $status);
                
                // Create approval histories based on status
                $this->createApprovalHistories($pengajuan, $user, $status);
            }
        }
    }
    
    private function createNotifications($pengajuan, $user, $status)
    {
        // Notification for the employee
        Notification::create([
            'user_id' => $user->id,
            'pengajuan_id' => $pengajuan->id,
            'judul' => 'Pengajuan Dibuat',
            'pesan' => 'Pengajuan Anda telah dibuat dan sedang menunggu persetujuan.',
            'dibaca' => rand(0, 1),
        ]);
        
        // Notification for supervisor
        if ($user->supervisor) {
            Notification::create([
                'user_id' => $user->supervisor->id,
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Pengajuan Baru',
                'pesan' => 'Ada pengajuan baru dari ' . $user->name . ' yang memerlukan persetujuan Anda.',
                'dibaca' => rand(0, 1),
            ]);
        }
        
        // Additional notifications based on status
        if (in_array($status, ['pending_kadep', 'pending_hrd', 'approved', 'rejected_kadep', 'rejected'])) {
            // Notification for department head
            if ($user->department && $user->department->head) {
                Notification::create([
                    'user_id' => $user->department->head->id,
                    'pengajuan_id' => $pengajuan->id,
                    'judul' => 'Pengajuan Memerlukan Persetujuan',
                    'pesan' => 'Pengajuan dari ' . $user->name . ' telah disetujui oleh atasan dan memerlukan persetujuan Anda.',
                    'dibaca' => rand(0, 1),
                ]);
            }
        }
        
        if (in_array($status, ['pending_hrd', 'approved', 'rejected'])) {
            // Notification for HRD
            $hrdUsers = User::where('role', 'hrd')->get();
            foreach ($hrdUsers as $hrd) {
                Notification::create([
                    'user_id' => $hrd->id,
                    'pengajuan_id' => $pengajuan->id,
                    'judul' => 'Pengajuan Memerlukan Persetujuan Final',
                    'pesan' => 'Pengajuan dari ' . $user->name . ' telah disetujui oleh Kepala Departemen dan memerlukan persetujuan final Anda.',
                    'dibaca' => rand(0, 1),
                ]);
            }
        }
    }
    
    private function createApprovalHistories($pengajuan, $user, $status)
    {
        // Approval by supervisor
        if (in_array($status, ['pending_kadep', 'pending_hrd', 'approved', 'rejected_kadep', 'rejected'])) {
            if ($user->supervisor) {
                ApprovalHistory::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approved_by' => $user->supervisor->id,
                    'level' => 'atasan',
                    'status' => 'approved',
                    'komentar' => 'Disetujui oleh atasan',
                ]);
            }
        }
        
        // Approval by department head
        if (in_array($status, ['pending_hrd', 'approved', 'rejected'])) {
            if ($user->department && $user->department->head) {
                ApprovalHistory::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approved_by' => $user->department->head->id,
                    'level' => 'kadep',
                    'status' => 'approved',
                    'komentar' => 'Disetujui oleh kepala departemen',
                ]);
            }
        }
        
        // Approval by HRD
        if ($status === 'approved') {
            $hrd = User::where('role', 'hrd')->first();
            if ($hrd) {
                ApprovalHistory::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approved_by' => $hrd->id,
                    'level' => 'hrd',
                    'status' => 'approved',
                    'komentar' => 'Disetujui oleh HRD',
                ]);
            }
        }
        
        // Rejection by supervisor
        if ($status === 'rejected_atasan') {
            if ($user->supervisor) {
                ApprovalHistory::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approved_by' => $user->supervisor->id,
                    'level' => 'atasan',
                    'status' => 'rejected',
                    'komentar' => 'Ditolak oleh atasan karena alasan tertentu',
                ]);
            }
        }
        
        // Rejection by department head
        if ($status === 'rejected_kadep') {
            if ($user->department && $user->department->head) {
                ApprovalHistory::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approved_by' => $user->department->head->id,
                    'level' => 'kadep',
                    'status' => 'rejected',
                    'komentar' => 'Ditolak oleh kepala departemen karena alasan tertentu',
                ]);
            }
        }
        
        // Rejection by HRD
        if ($status === 'rejected') {
            $hrd = User::where('role', 'hrd')->first();
            if ($hrd) {
                ApprovalHistory::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approved_by' => $hrd->id,
                    'level' => 'hrd',
                    'status' => 'rejected',
                    'komentar' => 'Ditolak oleh HRD karena alasan tertentu',
                ]);
            }
        }
    }
}