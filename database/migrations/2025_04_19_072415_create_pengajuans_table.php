<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('judul_kegiatan');
            $table->text('alasan');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->string('lokasi');
            $table->string('dokumen_pendukung')->nullable();
            $table->enum('status', ['draft', 'pending_atasan', 'rejected_atasan', 
                                  'pending_kadep', 'rejected_kadep', 
                                  'pending_hrd', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
