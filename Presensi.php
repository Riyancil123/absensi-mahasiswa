<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'user_id',
        'kelas_kuliah_id',
        'tanggal',
        'status',
        'keterangan',
        'latitude',
        'longitude',
        'jarak'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelasKuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'kelas_kuliah_id');
    }
}
