@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Pengajuan #{{ $pengajuan->id }}</h1>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <!-- Status Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Status Pengajuan:</h5>
                    @if($pengajuan->status == 'pending_atasan')
                        <span class="badge bg-warning fs-6">Menunggu Persetujuan Atasan</span>
                    @elseif($pengajuan->status == 'pending_kadep')
                        <span class="badge bg-warning fs-6">Menunggu Persetujuan Kepala Departemen</span>
                    @elseif($pengajuan->status == 'pending_hrd')
                        <span class="badge bg-warning fs-6">Menunggu Persetujuan HRD</span>
                    @elseif($pengajuan->status == 'approved')
                        <span class="badge bg-success fs-6">Disetujui</span>
                    @elseif($pengajuan->status == 'rejected_atasan')
                        <span class="badge bg-danger fs-6">Ditolak oleh Atasan</span>
                    @elseif($pengajuan->status == 'rejected_kadep')
                        <span class="badge bg-danger fs-6">Ditolak oleh Kepala Departemen</span>
                    @elseif($pengajuan->status == 'rejected')
                        <span class="badge bg-danger fs-6">Ditolak oleh HRD</span>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Tanggal Pengajuan:</h5>
                    <p>{{ $pengajuan->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengajuan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Judul Kegiatan</th>
                            <td>{{ $pengajuan->judul_kegiatan }}</td>
                        </tr>
                        <tr>
                            <th>Pegawai</th>
                            <td>{{ $pengajuan->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Departemen</th>
                            <td>{{ $pengajuan->user->department->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Mulai</th>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $pengajuan->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Alasan/Deskripsi</th>
                            <td>{{ $pengajuan->alasan }}</td>
                        </tr>
                        <tr>
                            <th>Dokumen Pendukung</th>
                            <td>
                                @if($pengajuan->dokumen_pendukung)
                                    <a href="{{ Storage::url($pengajuan->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-download me-1"></i> Unduh Dokumen
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada dokumen</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Approval Actions -->
            @if(($pengajuan->status == 'pending_atasan' && Auth::user()->isAtasan()) || 
                ($pengajuan->status == 'pending_kadep' && Auth::user()->isKepalaDepartemen()) || 
                ($pengajuan->status == 'pending_hrd' && Auth::user()->isHRD()))
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tindakan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Persetujuan</h5>
                </div>
                <div class="card-body">
                    @if($approvalHistories->count() > 0)
                        <ul class="list-group">
                            @foreach($approvalHistories as $history)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            @if($history->status == 'approved')
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger me-1"></i>
                                            @endif
                                            {{ ucfirst($history->level) }}
                                        </span>
                                        <small>{{ $history->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    <div>
                                        <strong>{{ $history->approver->name }}</strong>
                                    </div>
                                    @if($history->komentar)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            {{ $history->komentar }}
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Belum ada riwayat persetujuan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengajuan.approve', $pengajuan->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Setujui Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar (Opsional)</label>
                        <textarea class="form-control" id="komentar" name="komentar" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection