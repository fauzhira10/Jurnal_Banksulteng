<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\MasterCabang;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AtmMonitoringController extends Controller
{
    /**
     * Dashboard & Rekapitulasi Keluhan Mesin ATM
     */
    public function index(Request $request)
    {
        // 1. Deteksi Tahun yang Tersedia di Database
        $yearsFromDb = Jurnal::selectRaw('YEAR(tgl_transaksi) as yr')
            ->whereNotNull('tgl_transaksi')
            ->groupBy('yr')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->toArray();

        $currentYear = (int)date('Y');
        $validYears = array_filter(array_unique(array_merge([$currentYear, 2024, 2025, 2026], $yearsFromDb)), function($y) {
            return is_numeric($y) && $y >= 2020 && $y <= 2030;
        });
        rsort($validYears);
        $availableYears = array_values($validYears);

        $selectedYear = (int)$request->input('tahun', $availableYears[0] ?? $currentYear);
        $selectedMonth = $request->filled('bulan') ? (int)$request->input('bulan') : null;
        $selectedCabang = $request->input('master_cabang_id');
        $searchKeyword = trim((string)$request->input('q'));

        // 2. Base Query Data Keluhan Berdasarkan Filter
        $baseQuery = Jurnal::with(['masterCabang', 'masterTransaksi'])
            ->whereNotNull('terminal_transaksi')
            ->where('terminal_transaksi', '!=', '')
            ->where('terminal_transaksi', '!=', '-')
            ->whereYear('tgl_transaksi', $selectedYear);

        if (!empty($selectedMonth)) {
            $baseQuery->whereMonth('tgl_transaksi', $selectedMonth);
        }

        if (!empty($selectedCabang)) {
            $baseQuery->where('master_cabang_id', $selectedCabang);
        }

        if (!empty($searchKeyword)) {
            $baseQuery->where(function ($q) use ($searchKeyword) {
                $q->where('terminal_transaksi', 'LIKE', "%{$searchKeyword}%")
                  ->orWhereHas('masterCabang', function ($cQ) use ($searchKeyword) {
                      $cQ->where('nama_cabang', 'LIKE', "%{$searchKeyword}%")
                         ->orWhere('kode_cabang', 'LIKE', "%{$searchKeyword}%");
                  });
            });
        }

        // 3. Agregasi Data per Mesin ATM / Terminal
        $allJurnals = $baseQuery->get();

        $grouped = [];
        $totalKasus = 0;
        $totalNominal = 0;

        foreach ($allJurnals as $j) {
            $termKey = strtoupper(trim($j->terminal_transaksi));
            $cabangId = $j->master_cabang_id;
            $cabangNama = $j->masterCabang->nama_cabang ?? 'Kantor Cabang';
            $cabangKode = $j->masterCabang->kode_cabang ?? '-';
            $jenisTransaksi = $j->masterTransaksi->jenis_transaksi ?? 'Transaksi ATM';

            if (!isset($grouped[$termKey])) {
                $grouped[$termKey] = [
                    'terminal'       => $termKey,
                    'master_cabang_id' => $cabangId,
                    'cabang_nama'    => $cabangNama,
                    'cabang_kode'    => $cabangKode,
                    'total_keluhan'  => 0,
                    'total_nominal'  => 0,
                    'jenis_counts'   => [],
                ];
            }

            $grouped[$termKey]['total_keluhan']++;
            $grouped[$termKey]['total_nominal'] += (float)$j->nominal_transaksi;
            $totalKasus++;
            $totalNominal += (float)$j->nominal_transaksi;

            // Hitung frekuensi jenis transaksi di mesin ini
            if (!isset($grouped[$termKey]['jenis_counts'][$jenisTransaksi])) {
                $grouped[$termKey]['jenis_counts'][$jenisTransaksi] = 0;
            }
            $grouped[$termKey]['jenis_counts'][$jenisTransaksi]++;
        }

        // Urutkan mesin ATM berdasarkan jumlah keluhan terbanyak (DESC), lalu nominal (DESC)
        uasort($grouped, function ($a, $b) {
            if ($b['total_keluhan'] === $a['total_keluhan']) {
                return $b['total_nominal'] <=> $a['total_nominal'];
            }
            return $b['total_keluhan'] <=> $a['total_keluhan'];
        });

        // Format jenis keluhan dominan untuk setiap mesin
        $allAtms = [];
        foreach ($grouped as $key => $data) {
            arsort($data['jenis_counts']);
            $topIssues = [];
            $i = 0;
            foreach ($data['jenis_counts'] as $jName => $jCount) {
                if ($i++ >= 2) break;
                $cleanJName = preg_replace('/^(ATM_|MBANKING_|SMS BANKING |CRM_)/', '', $jName);
                $topIssues[] = "{$cleanJName} ({$jCount}x)";
            }
            $data['dominant_issues'] = implode(', ', $topIssues);
            $allAtms[] = $data;
        }

        // 4. Data Master untuk Filter & Ringkasan KPI
        $cabangs = MasterCabang::orderBy('kode_cabang')->get();
        $totalMesin = count($allAtms);
        $topAtm = $allAtms[0] ?? null;

        // 5. Pagination (Pilihan: 10, 50, 100 baris)
        $perPage = (int)$request->input('per_page', 10);
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($allAtms, ($currentPage - 1) * $perPage, $perPage);
        $atms = new LengthAwarePaginator(
            $currentItems,
            $totalMesin,
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('atm_monitoring', compact(
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'selectedCabang',
            'searchKeyword',
            'atms',
            'totalMesin',
            'totalKasus',
            'totalNominal',
            'topAtm',
            'cabangs',
            'monthNames',
            'perPage'
        ));
    }

    /**
     * Ekspor Rekapitulasi Mesin ATM Bermasalah ke Excel (.xlsx)
     */
    public function exportExcel(Request $request)
    {
        $selectedYear = (int)$request->input('tahun', date('Y'));
        $selectedMonth = $request->filled('bulan') ? (int)$request->input('bulan') : null;
        $selectedCabang = $request->input('master_cabang_id');
        $searchKeyword = trim((string)$request->input('q'));

        $query = Jurnal::with(['masterCabang', 'masterTransaksi'])
            ->whereNotNull('terminal_transaksi')
            ->where('terminal_transaksi', '!=', '')
            ->where('terminal_transaksi', '!=', '-')
            ->whereYear('tgl_transaksi', $selectedYear);

        if (!empty($selectedMonth)) {
            $query->whereMonth('tgl_transaksi', $selectedMonth);
        }
        if (!empty($selectedCabang)) {
            $query->where('master_cabang_id', $selectedCabang);
        }
        if (!empty($searchKeyword)) {
            $query->where(function ($q) use ($searchKeyword) {
                $q->where('terminal_transaksi', 'LIKE', "%{$searchKeyword}%")
                  ->orWhereHas('masterCabang', function ($cQ) use ($searchKeyword) {
                      $cQ->where('nama_cabang', 'LIKE', "%{$searchKeyword}%")
                         ->orWhere('kode_cabang', 'LIKE', "%{$searchKeyword}%");
                  });
            });
        }

        $allJurnals = $query->get();

        $grouped = [];
        foreach ($allJurnals as $j) {
            $termKey = strtoupper(trim($j->terminal_transaksi));
            if (!isset($grouped[$termKey])) {
                $grouped[$termKey] = [
                    'terminal'      => $termKey,
                    'cabang_nama'   => $j->masterCabang->nama_cabang ?? 'Kantor Cabang',
                    'cabang_kode'   => $j->masterCabang->kode_cabang ?? '-',
                    'total_keluhan' => 0,
                    'total_nominal' => 0,
                    'jenis_counts'  => [],
                ];
            }
            $grouped[$termKey]['total_keluhan']++;
            $grouped[$termKey]['total_nominal'] += (float)$j->nominal_transaksi;

            $jenisTransaksi = $j->masterTransaksi->jenis_transaksi ?? 'Transaksi ATM';
            if (!isset($grouped[$termKey]['jenis_counts'][$jenisTransaksi])) {
                $grouped[$termKey]['jenis_counts'][$jenisTransaksi] = 0;
            }
            $grouped[$termKey]['jenis_counts'][$jenisTransaksi]++;
        }

        uasort($grouped, function ($a, $b) {
            return $b['total_keluhan'] <=> $a['total_keluhan'];
        });

        $monthNames = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];
        $periodeText = !empty($selectedMonth) ? "BULAN {$monthNames[$selectedMonth]} {$selectedYear}" : "TAHUN {$selectedYear}";

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REKAP_ATM_' . $selectedYear);

        // Header Title
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', "LAPORAN REKAPITULASI KELUHAN MESIN ATM & TERMINAL - {$periodeText}");
        $sheet->getStyle('A2:E2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(28);

        // Table Header
        $headers = ['A4' => 'NO', 'B4' => 'KODE / NAMA TERMINAL ATM', 'C4' => 'KANTOR CABANG PENGELOLA', 'D4' => 'JUMLAH KELUHAN', 'E4' => 'TOTAL NOMINAL KELUHAN (RP)', 'F4' => 'JENIS GANGGUAN TERBANYAK'];
        foreach ($headers as $col => $title) {
            $sheet->setCellValue($col, $title);
        }
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EAF2EC']],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(22);

        $row = 5;
        $no = 1;
        $grandKeluhan = 0;
        $grandNominal = 0;

        foreach ($grouped as $data) {
            arsort($data['jenis_counts']);
            $topIssues = [];
            $i = 0;
            foreach ($data['jenis_counts'] as $jName => $jCount) {
                if ($i++ >= 2) break;
                $cleanJName = preg_replace('/^(ATM_|MBANKING_|SMS BANKING |CRM_)/', '', $jName);
                $topIssues[] = "{$cleanJName} ({$jCount}x)";
            }

            $sheet->setCellValue("A{$row}", $no);
            $sheet->setCellValue("B{$row}", $data['terminal']);
            $sheet->setCellValue("C{$row}", "{$data['cabang_kode']} - {$data['cabang_nama']}");
            $sheet->setCellValue("D{$row}", $data['total_keluhan']);
            $sheet->setCellValue("E{$row}", $data['total_nominal']);
            $sheet->setCellValue("F{$row}", implode(', ', $topIssues));

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getFont()->setBold(true);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getFont()->setBold(true);
            $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $grandKeluhan += $data['total_keluhan'];
            $grandNominal += $data['total_nominal'];
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
            $no++;
        }

        // Total Row
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->setCellValue("A{$row}", 'TOTAL KESELURUHAN');
        $sheet->setCellValue("D{$row}", $grandKeluhan);
        $sheet->setCellValue("E{$row}", $grandNominal);
        $sheet->setCellValue("F{$row}", '-');

        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10.5],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE599']],
        ]);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($row)->setRowHeight(24);

        // Borders
        $sheet->getStyle("A4:F{$row}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '999999']]],
        ]);

        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(26);
        $sheet->getColumnDimension('F')->setWidth(32);

        $fileName = 'Rekapitulasi_Keluhan_Mesin_ATM_BankSulteng_' . date('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            if (ob_get_length()) ob_end_clean();
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
