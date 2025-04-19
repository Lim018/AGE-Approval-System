@extends('layouts.app')

@section('title', 'Daftar Pengajuan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" data-aos="fade-right">Daftar Pengajuan</h1>
        @if(Auth::user()->isPegawai())
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary" data-aos="fade-left">
                <i class="fas fa-plus-circle me-1"></i> Buat Pengajuan Baru
            </a>
        @endif
    </div>
    
    <div class="card" data-aos="fade-up">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            @if(!Auth::user()->isPegawai())
                                <th>Pegawai</th>
                            @endif
                            <th>Judul Kegiatan</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $pengajuan)
                            <tr data-aos="fade-up" data-aos-delay="{{ $loop->index * 25 }}">
                                <td><span class="fw-medium">#{{ $pengajuan->id }}</span></td>
                                @if(!Auth::user()->isPegawai())
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <span class="text-primary fw-bold">{{ substr($pengajuan->user->name, 0, 1) }}</span>
                                            </div>
                                            <span>{{ $pengajuan->user->name }}</span>
                                        </div>
                                    </td>
                                @endif
                                <td>{{ $pengajuan->judul_kegiatan }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="far fa-calendar-plus text-success me-2"></i>
                                            <span>{{ \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('d M Y') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-minus text-danger me-2"></i>
                                            <span>{{ \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span>{{ $pengajuan->lokasi }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($pengajuan->status == 'pending_atasan')
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock me-1"></i> Menunggu Atasan
                                        </span>
                                    @elseif($pengajuan->status == 'pending_kadep')
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock me-1"></i> Menunggu Kepala Departemen
                                        </span>
                                    @elseif($pengajuan->status == 'pending_hrd')
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock me-1"></i> Menunggu HRD
                                        </span>
                                    @elseif($pengajuan->status == 'approved')
                                        <span class="badge badge-approved">
                                            <i class="fas fa-check-circle me-1"></i> Disetujui
                                        </span>
                                    @elseif($pengajuan->status == 'rejected_atasan')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak Atasan
                                        </span>
                                    @elseif($pengajuan->status == 'rejected_kadep')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak Kepala Departemen
                                        </span>
                                    @elseif($pengajuan->status == 'rejected')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak HRD
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isPegawai() ? '6' : '7' }}" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted">Tidak ada pengajuan</h5>
                                        @if(Auth::user()->isPegawai())
                                            <p class="text-muted mb-3">Anda belum membuat pengajuan apapun</p>
                                            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus-circle me-1"></i> Buat Pengajuan Baru
                                            </a>
                                        @else
                                            <p class="text-muted">Tidak ada pengajuan yang perlu ditinjau</p>
                                        @endif
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
@endsection