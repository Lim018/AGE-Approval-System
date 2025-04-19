@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Notifikasi</h1>
        <div>
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications as $notification)
                    <a href="{{ route('notifications.show', $notification->id) }}" class="list-group-item list-group-item-action {{ $notification->dibaca ? '' : 'bg-light' }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $notification->judul }}</h5>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $notification->pesan }}</p>
                        <small>
                            @if(!$notification->dibaca)
                                <span class="badge bg-primary">Baru</span>
                            @endif
                            Terkait pengajuan #{{ $notification->pengajuan_id }}
                        </small>
                    </a>
                @empty
                    <div class="text-center p-4">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p>Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection