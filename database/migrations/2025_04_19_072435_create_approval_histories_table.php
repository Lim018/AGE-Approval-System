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
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('approved_by');
            $table->enum('level', ['atasan', 'kadep', 'hrd']);
            $table->enum('status', ['approved', 'rejected']);
            $table->text('komentar')->nullable();
            $table->timestamps();
            
            $table->foreign('pengajuan_id')->references('id')->on('pengajuans');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};
