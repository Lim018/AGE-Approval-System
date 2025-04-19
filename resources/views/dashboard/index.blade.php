@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pengajuan</h5>
                    <h2 class="card-text">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Menunggu Persetujuan</h5>
                    <h2 class="card-text">{{ $stats['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Disetujui</h5>
                    <h2 class="card-text">{{ $stats['approved'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Ditolak</h5>
                    <h2 class="card-text">{{ $stats['rejected'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Pengajuan -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pengajuan Terbaru</h5>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul Kegiatan</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPengajuans as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->id }}</td>
                                <td>{{ $pengajuan->judul_kegiatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('d M Y') }}</td>
                                <td>
                                    @if(strpos($pengajuan->status, 'pending') !== false)
                                        <span class="badge bg-warning">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                    @elseif($pengajuan->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif(strpos($pengajuan->status, 'rejected') !== false)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada pengajuan terbaru</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Role-specific content -->
    @if(Auth::user()->isPegawai())
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Buat Pengajuan Baru</h5>
                    </div>
                    <div class="card-body">
                        <p>Buat pengajuan kegiatan baru dengan mengklik tombol di bawah ini:</p>
                        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Buat Pengajuan Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(Auth::user()->isAtasan() || Auth::user()->isKepalaDepartemen() || Auth::user()->isHRD())
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pengajuan Menunggu Persetujuan</h5>
                    </div>
                    <div class="card-body">
                        <p>Ada {{ $stats['pending'] }} pengajuan yang menunggu persetujuan Anda.</p>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-warning">
                            <i class="fas fa-clipboard-check me-1"></i> Lihat Pengajuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Notifications -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifikasi</h5>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($unreadNotificationsCount > 0)
                        <div class="alert alert-info">
                            Anda memiliki {{ $unreadNotificationsCount }} notifikasi yang belum dibaca.
                        </div>
                    @else
                        <p>Tidak ada notifikasi baru.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection