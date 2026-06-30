<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasKuliah extends Model
{
    use HasFactory;

    protected $table = 'kelas_kuliahs';

    protected $fillable = [
        'nama_mata_kuliah',
        'dosen_id',
        'ruangan',
        'status_absen'
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'kelas_kuliah_id');
    }
}
