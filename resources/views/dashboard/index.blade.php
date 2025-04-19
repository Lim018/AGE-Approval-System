@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-4" data-aos="fade-right">Dashboard</h1>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="50">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Total Pengajuan</h6>
                        <div class="icon-box bg-light rounded-circle p-3">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-0 counter">{{ $stats['total'] }}</h2>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Menunggu Persetujuan</h6>
                        <div class="icon-box bg-light rounded-circle p-3">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-0 counter">{{ $stats['pending'] }}</h2>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="150">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Disetujui</h6>
                        <div class="icon-box bg-light rounded-circle p-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-0 counter">{{ $stats['approved'] }}</h2>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['approved'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Ditolak</h6>
                        <div class="icon-box bg-light rounded-circle p-3">
                            <i class="fas fa-times-circle text-danger"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-0 counter">{{ $stats['rejected'] }}</h2>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['rejected'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Pengajuan -->
        <div class="col-lg-8 mb-4" data-aos="fade-up" data-aos-delay="50">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pengajuan Terbaru</h5>
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
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
                                    <tr class="align-middle">
                                        <td><span class="fw-medium">#{{ $pengajuan->id }}</span></td>
                                        <td>{{ $pengajuan->judul_kegiatan }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="far fa-calendar-alt text-muted me-2"></i>
                                                <span>{{ \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('d M Y') }}</span>
                                            </div>
                                        </td>
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
                                            <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-folder-open text-muted mb-3" style="font-size: 2rem;"></i>
                                                <p class="text-muted">Tidak ada pengajuan terbaru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notifications & Quick Actions -->
        <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifikasi</h5>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-bell me-1"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($unreadNotificationsCount > 0)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                Anda memiliki {{ $unreadNotificationsCount }} notifikasi yang belum dibaca.
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash text-muted mb-3" style="font-size: 2rem;"></i>
                            <p class="text-muted">Tidak ada notifikasi baru</p>
                        </div>
                    @endif
                    
                    <!-- Quick Actions -->
                    @if(Auth::user()->isPegawai())
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-plus-circle me-2"></i> Buat Pengajuan Baru
                            </a>
                        </div>
                    @endif
                    
                    @if(Auth::user()->isAtasan() || Auth::user()->isKepalaDepartemen() || Auth::user()->isHRD())
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Pengajuan Menunggu</h6>
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-light rounded-circle p-3 me-3">
                                    <i class="fas fa-clipboard-check text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $stats['pending'] }}</h5>
                                    <p class="text-muted mb-0">Menunggu persetujuan Anda</p>
                                </div>
                            </div>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary w-100 mt-3">
                                <i class="fas fa-eye me-2"></i> Lihat Pengajuan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200;
        
        counters.forEach(counter => {
            const animate = () => {
                const value = +counter.getAttribute('data-target');
                const data = +counter.innerText;
                
                const time = value / speed;
                if (data < value) {
                    counter.innerText = Math.ceil(data + time);
                    setTimeout(animate, 1);
                } else {
                    counter.innerText = value;
                }
            }
            
            // Set the target value
            counter.setAttribute('data-target', counter.innerText);
            counter.innerText = '0';
            
            // Start animation when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animate();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(counter);
        });
    });
</script>
@endsection
