<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = [
        'user_id', 'judul_kegiatan', 'alasan', 'waktu_mulai', 
        'waktu_selesai', 'lokasi', 'dokumen_pendukung', 'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function getCurrentApprovalLevel()
    {
        if ($this->status === 'pending_atasan') {
            return 'atasan';
        } else if ($this->status === 'pending_kadep') {
            return 'kadep';
        } else if ($this->status === 'pending_hrd') {
            return 'hrd';
        }
        
        return null;
    }
    
    public function isPending()
    {
        return in_array($this->status, ['pending_atasan', 'pending_kadep', 'pending_hrd']);
    }
    
    public function isRejected()
    {
        return in_array($this->status, ['rejected_atasan', 'rejected_kadep', 'rejected']);
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
}