<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function show(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return abort(403);
        }
        
        $notification->update(['dibaca' => true]);
        
        return redirect()->route('pengajuan.show', $notification->pengajuan_id);
    }
    
    public function markAllAsRead()
    {
        Auth::user()->notifications()->update(['dibaca' => true]);
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca.');
    }
}