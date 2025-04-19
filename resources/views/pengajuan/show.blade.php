@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" data-aos="fade-right">Detail Pengajuan #{{ $pengajuan->id }}</h1>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary" data-aos="fade-left">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <!-- Status Card -->
    <div class="card mb-4" data-aos="fade-up">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-muted mb-2">Status Pengajuan:</h5>
                    @if($pengajuan->status == 'pending_atasan')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-warning bg-opacity-10">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <span class="fw-medium">Menunggu Persetujuan Atasan</span>
                        </div>
                    @elseif($pengajuan->status == 'pending_kadep')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-warning bg-opacity-10">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <span class="fw-medium">Menunggu Persetujuan Kepala Departemen</span>
                        </div>
                    @elseif($pengajuan->status == 'pending_hrd')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-warning bg-opacity-10">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <span class="fw-medium">Menunggu Persetujuan HRD</span>
                        </div>
                    @elseif($pengajuan->status == 'approved')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-success bg-opacity-10">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="fw-medium">Disetujui</span>
                        </div>
                    @elseif($pengajuan->status == 'rejected_atasan')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-danger bg-opacity-10">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <span class="fw-medium">Ditolak oleh Atasan</span>
                        </div>
                    @elseif($pengajuan->status == 'rejected_kadep')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-danger bg-opacity-10">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <span class="fw-medium">Ditolak oleh Kepala Departemen</span>
                        </div>
                    @elseif($pengajuan->status == 'rejected')
                        <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-danger bg-opacity-10">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <span class="fw-medium">Ditolak oleh HRD</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="text-muted mb-2">Tanggal Pengajuan:</h5>
                    <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-light">
                        <i class="far fa-calendar-alt text-primary me-2"></i>
                        <span class="fw-medium">{{ $pengajuan->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Judul Kegiatan</div>
                        <div class="col-md-8 fw-medium">{{ $pengajuan->judul_kegiatan }}</div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Pegawai</div>
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    <span class="text-primary fw-bold">{{ substr($pengajuan->user->name, 0, 1) }}</span>
                                </div>
                                <span class="fw-medium">{{ $pengajuan->user->name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Departemen</div>
                        <div class="col-md-8 fw-medium">{{ $pengajuan->user->department->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Waktu Kegiatan</div>
                        <div class="col-md-8">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="far fa-calendar-plus text-success me-2"></i>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-minus text-danger me-2"></i>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Lokasi</div>
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span class="fw-medium">{{ $pengajuan->lokasi }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Alasan/Deskripsi</div>
                        <div class="col-md-8">
                            <div class="p-3 bg-light rounded">
                                {{ $pengajuan->alasan }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 text-muted">Dokumen Pendukung</div>
                        <div class="col-md-8">
                            @if($pengajuan->dokumen_pendukung)
                                <a href="{{ Storage::url($pengajuan->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-download me-1"></i> Unduh Dokumen
                                </a>
                            @else
                                <span class="text-muted">Tidak ada dokumen</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Approval Actions -->
            @if(($pengajuan->status == 'pending_atasan' && Auth::user()->isAtasan()) || 
                ($pengajuan->status == 'pending_kadep' && Auth::user()->isKepalaDepartemen()) || 
                ($pengajuan->status == 'pending_hrd' && Auth::user()->isHRD()))
                <div class="card mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tindakan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <i class="fas fa-check-circle me-1"></i> Setujui Pengajuan
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-1"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Approval History -->
            <div class="card" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Persetujuan</h5>
                </div>
                <div class="card-body p-0">
                    @if($approvalHistories->count() > 0)
                        <div class="timeline p-3">
                            @foreach($approvalHistories as $history)
                                <div class="timeline-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                    <div class="timeline-point {{ $history->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
                                        @if($history->status == 'approved')
                                            <i class="fas fa-check text-white"></i>
                                        @else
                                            <i class="fas fa-times text-white"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-medium">{{ ucfirst($history->level) }}</span>
                                            <small class="text-muted">{{ $history->created_at->format('d M Y H:i') }}</small>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge {{ $history->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $history->status == 'approved' ? 'Disetujui' : 'Ditolak' }}
                                            </span>
                                            <span class="ms-2">oleh {{ $history->approver->name }}</span>
                                        </div>
                                        @if($history->komentar)
                                            <div class="p-2 bg-light rounded small">
                                                {{ $history->komentar }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history text-muted mb-3" style="font-size: 2rem;"></i>
                            <p class="text-muted">Belum ada riwayat persetujuan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval History -->
<div class="card" data-aos="fade-up" data-aos-delay="300">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Persetujuan</h5>
    </div>
    <div class="card-body p-0">
        @if($approvalHistories->count() > 0)
            <div class="timeline p-3">
                @foreach($approvalHistories as $history)
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="timeline-point {{ $history->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
                            @if($history->status == 'approved')
                                <i class="fas fa-check text-white"></i>
                            @else
                                <i class="fas fa-times text-white"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">{{ ucfirst($history->level) }}</span>
                                <small class="text-muted">{{ $history->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="mb-2">
                                <span class="status-badge {{ $history->status == 'approved' ? 'status-approved' : 'status-rejected' }}">
                                    {{ $history->status == 'approved' ? 'Disetujui' : 'Ditolak' }}
                                </span>
                                <span class="ms-2">oleh {{ $history->approver->name }}</span>
                            </div>
                            @if($history->komentar)
                                <div class="p-2 bg-light rounded small">
                                    {{ $history->komentar }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-history text-muted mb-3" style="font-size: 2rem;"></i>
                <p class="text-muted">Belum ada riwayat persetujuan</p>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('pengajuan.reject', $pengajuan->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Timeline styling - fixed positioning */
    .timeline {
        position: relative;
        padding-left: 40px; /* Increased padding to accommodate icons */
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 19px; /* Adjusted to center the line with the icons */
        top: 15px; /* Start from the middle of the first icon */
        height: calc(100% - 30px); /* Adjusted height to connect icons properly */
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 25px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-point {
        position: absolute;
        left: -40px; /* Adjusted to align properly */
        top: 0;
        width: 24px; /* Reduced size */
        height: 24px; /* Reduced size */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        box-shadow: 0 0 0 4px white; /* Add white border to separate from line */
    }
    
    .timeline-point i {
        font-size: 12px; /* Smaller icon */
    }
    
    .timeline-content {
        background-color: #fff;
        border-radius: 5px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 5px;
    }
    
    /* Status badges */
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-approved {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #842029;
    }
</style>
@endsection

@section('scripts')
<script>
    // Modal animation
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            setTimeout(() => {
                const modalDialog = this.querySelector('.modal-dialog');
                modalDialog.style.transition = 'transform 0.3s ease-out';
                modalDialog.style.transform = 'translateY(0)';
            }, 50);
        });
        
        modal.addEventListener('hide.bs.modal', function() {
            const modalDialog = this.querySelector('.modal-dialog');
            modalDialog.style.transform = 'translateY(-50px)';
        });
    });
</script>
@endsection