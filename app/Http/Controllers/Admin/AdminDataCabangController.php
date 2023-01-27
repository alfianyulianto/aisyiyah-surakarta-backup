<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Daerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDataCabangController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('admin.cabang.index', [
      'cabang' => Cabang::orderBy('created_at', 'desc')->get()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.cabang.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'id_cabang' => ['required', 'min:9', 'max:9', 'unique:App\Models\Cabang,id_cabang'],
      'nama_cabang' => ['required', 'min:5'],
      'alamat_cabang' => ['required', 'min:10'],
      'sk_pimp_cabang' => ['required', 'mimes:pdf']
    ]);

    $validated['daerah_id_daerah'] = Daerah::get()->first()->id_daerah;

    // simpan file yang diupload
    $file = $request->file('sk_pimp_cabang');
    $validated['sk_pimp_cabang'] = $file->storeAs('sk pimpinan cabang',  'sk-pimpinan-cabang_' . $request->nama_cabang . '_' . date('d-m-Y') . '.' . $file->getClientOriginalExtension());

    // insert ke table cabang
    Cabang::create($validated);

    return redirect('/data/cabang')->with('message_cabang', 'Berhasil menambahkan cabang ' . $request->nama_cabang);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    abort(404);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Cabang $cabang)
  {
    return view('admin.cabang.edit', [
      'cabang' => $cabang
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Cabang $cabang)
  {
    $role = [
      'id_cabang' => ['required', 'min:9', 'max:9'],
      'nama_cabang' => ['required', 'min:5'],
      'alamat_cabang' => ['required', 'min:10'],
      'sk_pimp_cabang' => ['file']
    ];

    // cek apakah $request->id_cabang sama dengan id_cabang pada tabel cabang
    if ($request->id_cabang != $cabang->id_cabang) {
      $role['id_cabang'] = ['required', 'min:9', 'max:9', 'unique:App\Models\Cabang,id_cabang'];
    }

    // cek jika user mengganti nama cabang
    if ($request->nama_cabang != $cabang->nama_cabang) {
      $role['sk_pimp_cabang'] = ['required', 'mimes:pdf'];
    }

    // cek validasi
    $validated = $request->validate($role);

    // cek jika user mengupload ulang sk_pimp_cabang
    if ($request->sk_pimp_cabang) {
      // simpan file yang diupload
      $file = $request->file('sk_pimp_cabang');
      // hapus file sk_pimp_cabang dari tabel cabang
      Storage::delete($cabang->sk_pimp_cabang);
      $validated['sk_pimp_cabang'] = $file->storeAs('sk pimpinan cabang',  'sk-pimpinan-cabang_' . $request->nama_cabang . '_' . date('d-m-Y') . '.' . $file->getClientOriginalExtension());
    }

    // update data ke tabel cabang
    $cabang->update($validated);

    return redirect('/data/cabang')->with('message_cabang', 'Data cabang ' . $request->nama_cabang . ' berhasil diubah.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  public function download(Cabang $cabang)
  {
    return Storage::download($cabang->sk_pimp_cabang);
  }
}
