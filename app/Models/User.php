<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'department_id', 'supervisor_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    
    public function subordinates()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }
    
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
    
    public function approvals()
    {
        return $this->hasMany(ApprovalHistory::class, 'approved_by');
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function isPegawai()
    {
        return $this->role === 'pegawai';
    }
    
    public function isAtasan()
    {
        return $this->role === 'atasan';
    }
    
    public function isKepalaDepartemen()
    {
        return $this->role === 'kepala_departemen';
    }
    
    public function isHRD()
    {
        return $this->role === 'hrd';
    }
}