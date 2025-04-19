<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    protected $fillable = [
        'pengajuan_id', 'approved_by', 'level', 'status', 'komentar'
    ];
    
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}