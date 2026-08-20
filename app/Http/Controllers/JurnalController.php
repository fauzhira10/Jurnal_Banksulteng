<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\MasterCabang;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    /**
     * Menampilkan daftar data jurnal keluhan dengan fitur pencarian, filter, dan statistik
     */
    public function index(Request $request)
    {
        $query = Jurnal::with(['masterCabang', 'masterTransaksi']);

        // 1. Filter Pencarian Keyword Multi-Field (Case-Insensitive & Multi-Word)
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $terms = array_filter(explode(' ', $keyword));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->where('nama_nasabah', 'LIKE', "%{$term}%")
                             ->orWhere('no_resi', 'LIKE', "%{$term}%")
                             ->orWhere('no_rekening', 'LIKE', "%{$term}%")
                             ->orWhere('no_kartu', 'LIKE', "%{$term}%")
                             ->orWhere('no_tiket', 'LIKE', "%{$term}%")
                             ->orWhere('terminal_transaksi', 'LIKE', "%{$term}%")
                             ->orWhereHas('masterCabang', function($cQ) use ($term) {
                                 $cQ->where('nama_cabang', 'LIKE', "%{$term}%")
                                    ->orWhere('kode_cabang', 'LIKE', "%{$term}%");
                             })
                             ->orWhereHas('masterTransaksi', function($tQ) use ($term) {
                                 $tQ->where('jenis_transaksi', 'LIKE', "%{$term}%")
                                    ->orWhere('channel', 'LIKE', "%{$term}%");
                             });
                    });
                }
            });
        }

        // 2. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filter Cabang
        if ($request->filled('master_cabang_id')) {
            $query->where('master_cabang_id', $request->master_cabang_id);
        }

        // 4. Filter Rentang Tanggal Transaksi
        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_transaksi', '>=', $request->tgl_dari);
        }
        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_transaksi', '<=', $request->tgl_sampai);
        }

        // Urutkan data terbaru terlebih dahulu
        $jurnals = $query->latest('id')->paginate(10)->withQueryString();

        // Ambil data Master untuk pilihan filter
        $cabangs = MasterCabang::orderBy('kode_cabang')->get();
        $transaksis = MasterTransaksi::orderBy('jenis_transaksi')->get();

        // Hitung Ringkasan Statistik
        $stats = [
            'total'    => Jurnal::count(),
            'menunggu' => Jurnal::where('status', 'Menunggu')->count(),
            'success'  => Jurnal::where('status', 'Success')->count(),
            'done'     => Jurnal::where('status', 'Done')->count(),
            'rejected' => Jurnal::where('status', 'Rejected')->count(),
        ];

        return view('jurnal_data', compact('jurnals', 'cabangs', 'transaksis', 'stats'));
    }

    /**
     * Menampilkan formulir input jurnal keluhan
     */
    public function create()
    {
        $cabangs = MasterCabang::orderBy('kode_cabang')->get();
        $transaksis = MasterTransaksi::orderBy('jenis_transaksi')->get();

        return view('jurnal_form', compact('cabangs', 'transaksis'));
    }

    /**
     * Menyimpan data jurnal dengan Validasi Hard (Anti-Duplikat)
     */
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
            'nominal_transaksi'  => 'required|numeric|min:0',
            'keterangan_log'     => 'nullable|string',
            'status'             => 'required|string|in:Menunggu,Done,Rejected,Success'
        ], [
            'no_resi.unique' => 'Gagal! Keluhan atas nama nasabah ini dengan No. Resi dan Tanggal tersebut sudah pernah dijurnal.'
        ]);

        Jurnal::create($request->all());

        return redirect()->route('jurnal.index')->with('success', 'Jurnal keluhan nasabah berhasil disimpan!');
    }

    /**
     * API AJAX untuk mengambil data master transaksi (Biaya admin & channel)
     */
    public function getDetailTransaksi($id)
    {
        $transaksi = MasterTransaksi::find($id);
        return response()->json($transaksi);
    }

    /**
     * API AJAX untuk mengambil rincian 1 data jurnal keluhan
     */
    public function getDetailJurnal($id)
    {
        $jurnal = Jurnal::with(['masterCabang', 'masterTransaksi'])->find($id);
        return response()->json($jurnal);
    }

    /**
     * Menghapus data jurnal keluhan
     */
    public function destroy($id)
    {
        $jurnal = Jurnal::findOrFail($id);

        if (method_exists($jurnal, 'auditTrails')) {
            $jurnal->auditTrails()->delete();
        }

        $namaNasabah = $jurnal->nama_nasabah;
        $noResi = $jurnal->no_resi;

        $jurnal->delete();

        return redirect()->route('jurnal.index')->with('success', "Data jurnal keluhan {$namaNasabah} (No. Resi: {$noResi}) berhasil dihapus.");
    }
}