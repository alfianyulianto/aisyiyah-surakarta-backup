<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kader extends Model
{
  use HasFactory;

  protected $table = "kader";
  protected $primaryKey = "nik";

  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = ['nik', 'user_id', 'daerah_id_daerah', 'cabang_id_cabang', 'ranting_id_ranting', 'no_kta', 'no_ktm', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat_asal_ktp', 'alamat_rumah_tinggal', 'status_pernikahan', 'pekerjaan', 'email', 'no_ponsel', 'pendidikan_terakhir', 'foto'];

  public function daerah()
  {
    return $this->hasOne(Daerah::class);
  }

  public function cabang()
  {
    return $this->hasOne(Cabang::class);
  }
  public function ranting()
  {
    return $this->hasOne(Ranting::class);
  }

  public function kader_memiliki_jabatan()
  {
    return $this->hasMany(KaderJabatan::class);
  }

  public function kader_memiliki_ortom()
  {
    return $this->hasMany(KaderOrtom::class);
  }

  public function kader_mimiliki_potensi()
  {
    return $this->hasMany(KaderPotensi::class);
  }

  public function pendidikan_terakhir()
  {
    return $this->belongsTo(PendidikanTerakhir::class, 'pendidikan_terakhir_id_pendidikan_terakhir', 'id_pendidikan_terakhir');
  }
}
