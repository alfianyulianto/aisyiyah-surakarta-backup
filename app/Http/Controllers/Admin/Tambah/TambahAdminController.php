<?php

namespace App\Http\Controllers\Admin\Tambah;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Daerah;
use App\Models\Ranting;
use Illuminate\Http\Request;

class TambahAdminController extends Controller
{
  public function index()
  {
    return view('admin.tambah_admin.index', [
      'daerah' => Daerah::where('id_daerah', 'ska-1')->get(),
      'cabang' => Cabang::orderBy('nama_cabang', 'asc')->get(),
      'ranting' => Ranting::orderBy('nama_ranting', 'asc')->get()
    ]);
  }
}
