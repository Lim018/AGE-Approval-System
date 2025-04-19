@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" data-aos="fade-right">Buat Pengajuan Baru</h1>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary" data-aos="fade-left">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <div class="card" data-aos="fade-up">
        <div class="card-body">
            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" id="pengajuanForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="50">
                        <label for="judul_kegiatan" class="form-label fw-medium">Judul Kegiatan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-heading text-primary"></i>
                            </span>
                            <input type="text" class="form-control @error('judul_kegiatan') is-invalid @enderror" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" required>
                            @error('judul_kegiatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <label for="lokasi" class="form-label fw-medium">Lokasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </span>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required>
                            @error('lokasi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="150">
                        <label for="waktu_mulai" class="form-label fw-medium">Waktu Mulai <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="far fa-calendar-plus text-success"></i>
                            </span>
                            <input type="date" class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                            @error('waktu_mulai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <label for="waktu_selesai" class="form-label fw-medium">Waktu Selesai <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="far fa-calendar-minus text-danger"></i>
                            </span>
                            <input type="date" class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                            @error('waktu_selesai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-4" data-aos="fade-up" data-aos-delay="250">
                    <label for="alasan" class="form-label fw-medium">Alasan/Deskripsi <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-align-left text-primary"></i>
                        </span>
                        <textarea class="form-control @error('alasan') is-invalid @enderror" id="alasan" name="alasan" rows="4" required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4" data-aos="fade-up" data-aos-delay="300">
                    <label for="dokumen_pendukung" class="form-label fw-medium">Dokumen Pendukung</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-file-upload text-primary"></i>
                        </span>
                        <input type="file" class="form-control @error('dokumen_pendukung') is-invalid @enderror" id="dokumen_pendukung" name="dokumen_pendukung">
                        @error('dokumen_pendukung')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-text">Format yang diperbolehkan: PDF, DOC, DOCX. Maksimal 2MB.</div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4" data-aos="fade-up" data-aos-delay="350">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane me-1"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validasi tanggal selesai harus setelah tanggal mulai
    document.getElementById('waktu_mulai').addEventListener('change', function() {
        document.getElementById('waktu_selesai').min = this.value;
        
        // Reset tanggal selesai jika sebelum tanggal mulai
        const waktuSelesai = document.getElementById('waktu_selesai');
        if (waktuSelesai.value && waktuSelesai.value < this.value) {
            waktuSelesai.value = this.value;
        }
    });
    
    // Form submission animation
    document.getElementById('pengajuanForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
        submitBtn.disabled = true;
    });
    
    // Character counter for alasan
    document.getElementById('alasan').addEventListener('input', function() {
        const maxLength = 500;
        const currentLength = this.value.length;
        const remainingChars = maxLength - currentLength;
        
        let counterElement = document.getElementById('charCounter');
        if (!counterElement) {
            counterElement = document.createElement('small');
            counterElement.id = 'charCounter';
            counterElement.className = 'form-text';
            this.parentNode.appendChild(counterElement);
        }
        
        counterElement.textContent = `${currentLength}/${maxLength} karakter`;
        
        if (remainingChars < 50) {
            counterElement.className = 'form-text text-warning';
        } else {
            counterElement.className = 'form-text';
        }
    });
</script>
@endsection