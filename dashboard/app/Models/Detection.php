<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'lokasi',
        'nama_pelaku',
        'waktu_kejadian',
        'gambar_bukti',
        'jenis_bukti',
        'kategori_sampah',
        'status_indikasi',
        'status_validasi',
        'confidence_score',
        'keterangan',
        'tindak_lanjut',
    ];

    protected $casts = [
        'waktu_kejadian' => 'datetime',
    ];
}
