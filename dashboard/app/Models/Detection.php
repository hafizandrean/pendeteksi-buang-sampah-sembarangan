<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'lokasi',
        'waktu_kejadian',
        'gambar_bukti',
        'jenis_bukti',
        'status_indikasi',
        'status_validasi',
        'keterangan',
        'tindak_lanjut',
    ];

    protected $casts = [
        'waktu_kejadian' => 'datetime',
    ];
}
