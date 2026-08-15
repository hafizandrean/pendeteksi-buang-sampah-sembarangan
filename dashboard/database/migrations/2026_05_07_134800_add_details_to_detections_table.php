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
        Schema::table('detections', function (Blueprint $table) {
            $table->string('nama_pelaku')->nullable()->after('lokasi');
            $table->string('kategori_sampah')->nullable()->after('jenis_bukti');
            $table->float('confidence_score')->nullable()->after('status_validasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            $table->dropColumn(['nama_pelaku', 'kategori_sampah', 'confidence_score']);
        });
    }
};
