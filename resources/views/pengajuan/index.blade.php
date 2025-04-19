@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Daftar Pengajuan</h1>
        @if(Auth::user()->isPegawai())
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Buat Pengajuan Baru
            </a>
        @endif
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            @if(!Auth::user()->isPegawai())
                                <th>Pegawai</th>
                            @endif
                            <th>Judul Kegiatan</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->id }}</td>
                                @if(!Auth::user()->isPegawai())
                                    <td>{{ $pengajuan->user->name }}</td>
                                @endif
                                <td>{{ $pengajuan->judul_kegiatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('d M Y') }}</td>
                                <td>{{ $pengajuan->lokasi }}</td>
                                <td>
                                    @if($pengajuan->status == 'pending_atasan')
                                        <span class="badge bg-warning">Menunggu Atasan</span>
                                    @elseif($pengajuan->status == 'pending_kadep')
                                        <span class="badge bg-warning">Menunggu Kepala Departemen</span>
                                    @elseif($pengajuan->status == 'pending_hrd')
                                        <span class="badge bg-warning">Menunggu HRD</span>
                                    @elseif($pengajuan->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($pengajuan->status == 'rejected_atasan')
                                        <span class="badge bg-danger">Ditolak Atasan</span>
                                    @elseif($pengajuan->status == 'rejected_kadep')
                                        <span class="badge bg-danger">Ditolak Kepala Departemen</span>
                                    @elseif($pengajuan->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak HRD</span>
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
                                <td colspan="{{ Auth::user()->isPegawai() ? '7' : '8' }}" class="text-center">Tidak ada pengajuan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection