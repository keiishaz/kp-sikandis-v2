<?php

namespace App\Services;

use App\Contracts\Repositories\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepositoryInterface $dashboardRepo,
        private readonly LogReaderService             $logReader,
    ) {}

    public function getSummary(): array
    {
        $kendaraanStats  = $this->dashboardRepo->kendaraanStats();
        $pajakStats      = $this->dashboardRepo->pajakStats();
        $pemegangStats   = $this->dashboardRepo->pemegangStats($kendaraanStats['total']);
        $qrStats         = $this->dashboardRepo->qrStats();
        $qrChart         = $this->dashboardRepo->qrChartData();
        $masterStats     = $this->dashboardRepo->masterDataStats();

        return [
            // Kendaraan
            'totalKendaraan'          => $kendaraanStats['total'],
            'kendaraanAktif'          => $kendaraanStats['aktif'],
            'kendaraanTidakAktif'     => $kendaraanStats['tidak_aktif'],
            'kendaraanJabatan'        => $kendaraanStats['jabatan'],
            'kendaraanOperasional'    => $kendaraanStats['operasional'],
            // Pajak
            'pajakAktif'              => $pajakStats['aktif'],
            'pajakMati'               => $pajakStats['mati'],
            'pajakTidakAda'           => $pajakStats['tidak_ada'],
            'pajakSegera'             => $pajakStats['segera'],
            // Pemegang
            'kendaraanDenganPemegang' => $pemegangStats['dengan_pemegang'],
            'kendaraanTanpaPemegang'  => $pemegangStats['tanpa_pemegang'],
            // QR
            'totalQr'                 => $qrStats['total'],
            'totalScan'               => $qrStats['scan'],
            'topQr'                   => $qrStats['top'],
            'qrChartBulan'            => $qrChart['bulan'],
            'qrChartData'             => $qrChart['data'],
            // Master Data
            'totalPegawai'            => $masterStats['pegawai'],
            'totalUnit'               => $masterStats['unit'],
            'totalKategori'           => $masterStats['kategori'],
            'totalOperator'           => $masterStats['operator'],
            // Lists
            'distribusiKategori'      => $this->dashboardRepo->distribusiKategori(),
            'kendaraanTerbaru'        => $this->dashboardRepo->kendaraanTerbaru(),
            'daftarPajakSegera'       => $this->dashboardRepo->daftarPajakSegera(),
            'recentLogs'              => $this->getRecentLogs(),
        ];
    }

    private function getRecentLogs(): \Illuminate\Support\Collection
    {
        return collect(
            $this->logReader->readAktivitasLog(null, now()->format('Y-m-d'), 8, 1)->items()
        );
    }
}
