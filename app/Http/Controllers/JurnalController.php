<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    // Fungsi untuk menyimpan jurnal dengan Validasi Hard (Anti-Duplikat)
    public function store(Request $request)
    {
        $request->validate([
            'nama_nasabah'   => 'required',
            'no_resi'        => 'required|unique:jurnals,no_resi,NULL,id,nama_nasabah,' . $request->nama_nasabah . ',tgl_transaksi,' . $request->tgl_transaksi,
            'tgl_transaksi'  => 'required|date',
            'no_rekening'    => 'required',
            'nominal_transaksi' => 'required'
        ], [
            
            'no_resi.unique' => 'Gagal! Keluhan atas nama nasabah ini dengan No. Resi dan Tanggal tersebut sudah pernah dijurnal.'
        ]);

        Jurnal::create($request->all());

        return back()->with('success', 'Jurnal keluhan berhasil disimpan!');
    }

    public function getDetailTransaksi($id)
    {
        $transaksi = MasterTransaksi::find($id);
        return response()->json($transaksi);
    }
}