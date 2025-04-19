@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" data-aos="fade-right">Notifikasi</h1>
        <div data-aos="fade-left">
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>
    
    <div class="card" data-aos="fade-up">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <a href="{{ route('notifications.show', $notification->id) }}" 
                       class="list-group-item list-group-item-action p-3 {{ $notification->dibaca ? '' : 'bg-light' }}"
                       data-aos="fade-up" data-aos-delay="{{ $loop->index * 25 }}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if(!$notification->dibaca)
                                    <span class="badge rounded-pill bg-primary me-3 pulse-animation">Baru</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary me-3">Dibaca</span>
                                @endif
                                <div>
                                    <h5 class="mb-1 fw-semibold">{{ $notification->judul }}</h5>
                                    <p class="mb-1">{{ $notification->pesan }}</p>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-file-alt me-1"></i>
                                        <span>Pengajuan #{{ $notification->pengajuan_id }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                <div class="mt-2">
                                    <span class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5" data-aos="fade-up">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5>Tidak ada notifikasi</h5>
                        <p class="text-muted">Anda akan melihat notifikasi ketika ada aktivitas baru</p>
                    </div>
                @endforelse
            </div>
            
            <div class="p-3">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .pulse-animation {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
</style>
@endsection