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
            'nama_nasabah'       => 'required|string|max:255',
            'no_resi'            => 'required|string|max:255|unique:jurnals,no_resi,NULL,id,nama_nasabah,' . $request->nama_nasabah . ',tgl_transaksi,' . $request->tgl_transaksi,
            'no_rekening'        => 'required|string|max:255',
            'tgl_terima'         => 'required|date',
            'tgl_transaksi'      => 'required|date',
            'tgl_selesai'        => 'nullable|date',
            'no_tiket'           => 'nullable|string|max:255',
            'no_kartu'           => 'nullable|string|max:255',
            'master_cabang_id'   => 'required|exists:master_cabangs,id',
            'master_transaksi_id'=> 'required|exists:master_transaksis,id',
            'terminal_transaksi' => 'nullable|string|max:255',
            'nominal_transaksi'  => 'required|numeric',
            'keterangan_log'     => 'nullable|string',
            'status'             => 'required|string|in:Menunggu,Done,Rejected,Success'
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