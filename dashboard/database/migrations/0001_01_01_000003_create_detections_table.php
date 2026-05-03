<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('detections', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->dateTime('waktu_kejadian')->nullable();
            $table->string('gambar_bukti')->nullable();
            $table->string('jenis_bukti')->nullable();
            $table->string('status_indikasi')->default('Normal');
            $table->string('status_validasi')->default('Belum Divalidasi');
            $table->text('keterangan')->nullable();
            $table->string('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('detections');
    }
};
