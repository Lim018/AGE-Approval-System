<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'pengajuan_id', 'judul', 'pesan', 'dibaca'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}