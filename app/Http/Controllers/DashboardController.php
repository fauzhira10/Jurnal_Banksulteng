<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\MasterCabang;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Sistem Jurnal Keluhan
     */
    public function index(Request $request)
    {
        // 1. Tahun yang tersedia untuk filter jika diinginkan
        $yearsFromDb = Jurnal::selectRaw('YEAR(tgl_transaksi) as yr')
            ->whereNotNull('tgl_transaksi')
            ->groupBy('yr')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->toArray();

        $currentYear = (int)date('Y');
        $validYears = array_filter(array_unique(array_merge([$currentYear, 2024, 2025, 2026], $yearsFromDb)), function ($y) {
            return is_numeric($y) && $y >= 2020 && $y <= 2030;
        });
        rsort($validYears);
        $availableYears = array_values($validYears);

        $selectedYear = $request->filled('tahun') ? (int)$request->input('tahun') : null;

        // 2. Base Query
        $baseQuery = Jurnal::query();
        if (!empty($selectedYear)) {
            $baseQuery->whereYear('tgl_transaksi', $selectedYear);
        }

        // 3. Empat Metrik Utama
        $totalKasus = (clone $baseQuery)->count();
        $totalNominal = (float)(clone $baseQuery)->sum('nominal_transaksi');

        // Agregasi Mesin ATM
        $atmQuery = (clone $baseQuery)
            ->whereNotNull('terminal_transaksi')
            ->where('terminal_transaksi', '!=', '')
            ->where('terminal_transaksi', '!=', '-');

        $atmGrouped = (clone $atmQuery)
            ->select('terminal_transaksi', DB::raw('COUNT(*) as total_keluhan'), DB::raw('SUM(nominal_transaksi) as total_nominal'))
            ->groupBy('terminal_transaksi')
            ->orderByDesc('total_keluhan')
            ->orderByDesc('total_nominal')
            ->get();

        $totalMesin = $atmGrouped->count();
        $topAtm = $atmGrouped->first();

        // Top 5 Mesin ATM Bermasalah
        $topAtms = $atmGrouped->take(5);

        // 4. Ringkasan Status Penyelesaian
        $statusStats = [
            'menunggu' => (clone $baseQuery)->where('status', 'Menunggu')->count(),
            'success'  => (clone $baseQuery)->where('status', 'Success')->count(),
            'done'     => (clone $baseQuery)->where('status', 'Done')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'Rejected')->count(),
        ];

        // 5. Top 5 Jenis Gangguan / Transaksi Terbanyak
        $topIssues = (clone $baseQuery)
            ->join('master_transaksis', 'jurnals.master_transaksi_id', '=', 'master_transaksis.id')
            ->select('master_transaksis.jenis_transaksi', 'master_transaksis.channel', DB::raw('COUNT(*) as total_kasus'), DB::raw('SUM(jurnals.nominal_transaksi) as total_nominal'))
            ->groupBy('master_transaksis.id', 'master_transaksis.jenis_transaksi', 'master_transaksis.channel')
            ->orderByDesc('total_kasus')
            ->limit(5)
            ->get();

        // 6. Lima Data Keluhan Nasabah Terbaru
        $recentJurnals = (clone $baseQuery)
            ->with(['masterCabang', 'masterTransaksi'])
            ->latest('id')
            ->limit(5)
            ->get();

        // 7. Total Master Data
        $totalCabang = MasterCabang::count();

        return view('dashboard', compact(
            'totalKasus',
            'totalMesin',
            'topAtm',
            'totalNominal',
            'statusStats',
            'topAtms',
            'topIssues',
            'recentJurnals',
            'totalCabang',
            'availableYears',
            'selectedYear'
        ));
    }
}
