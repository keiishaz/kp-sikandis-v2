<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;

class LogReaderService
{
    /**
     * Membaca log aktivitas dari file secara reverse dan menerapkan filter & pagination.
     */
    public function readAktivitasLog($search = null, $date = null, $perPage = 15, $page = 1)
    {
        $path = storage_path('logs/aktivitas.txt');
        if (!File::exists($path)) {
            return new LengthAwarePaginator([], 0, $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines); // LIFO (Terbaru di atas)

        $data = [];
        foreach ($lines as $line) {
            // Contoh baris: [2026-02-24 00:03:36] TAMBAH UNIT — User: Keisha Azzahra — Entitas: Unit — ID: 1 — Nama: Dinas Kominfo
            // Kita parse waktu aslinya, lalu render. Asumsi aslinya sudah WIB atau UTC sesuai format saat log ditulis.
            // Memaksa parsing Carbon ke format WIB:
            if (preg_match('/^\[(.*?)\] (.*)/', $line, $matches)) {
                $timeString = $matches[1];
                $messageStr = $matches[2];
                
                try {
                    // Karena diminta konversi WIB jika asalnya beda, namun kita akan anggap string text ini sebagai input Carbon.
                    $carbonDate = Carbon::parse($timeString)->timezone('Asia/Jakarta');
                } catch (\Exception $e) {
                    $carbonDate = null;
                }

                // Filter Date
                if ($date && $carbonDate && $carbonDate->format('Y-m-d') !== $date) {
                    continue;
                }

                // Filter Search
                if ($search && stripos($line, $search) === false) {
                    continue;
                }

                // Parsing rincian berdasarkan pemisah ' ' atau ' — '
                $parts = explode(' — ', $messageStr);
                $aksi = $parts[0] ?? '-';
                $user = isset($parts[1]) ? str_replace('User: ', '', $parts[1]) : '-';
                $entitas = isset($parts[2]) ? str_replace('Entitas: ', '', $parts[2]) : '-';
                
                $keteranganRaw = '';
                if (count($parts) > 3) {
                    $keteranganRaw = implode(' | ', array_slice($parts, 3));
                }

                $keteranganHtml = '';
                if ($keteranganRaw) {
                    $idDoc = '';
                    if (preg_match('/ID: (\d+)/', $keteranganRaw, $mId)) {
                        $idDoc = $mId[1];
                    }

                    if (str_contains($aksi, 'EDIT') && preg_match('/Dari:\s*(.*?)\s*→\s*(.*)$/', $keteranganRaw, $mEdit)) {
                        $htmlFrom = htmlspecialchars($mEdit[1], ENT_QUOTES);
                        $htmlTo = htmlspecialchars($mEdit[2], ENT_QUOTES);
                        $keteranganHtml = "Mengubah data <strong>{$entitas}</strong> dari <strong>{$htmlFrom}</strong> menjadi <strong>{$htmlTo}</strong>.";
                    } elseif (str_contains($aksi, 'TAMBAH')) {
                        // Ambil detail di sebelah kanan ID (misal Nama: ABC | NIP: 123)
                        $detailParts = array_slice($parts, 4);
                        if (!empty($detailParts)) {
                            $keteranganHtml = "Menambahkan data <strong>{$entitas}</strong> baru dengan rincian: <strong>" . htmlspecialchars(implode(' | ', $detailParts), ENT_QUOTES) . "</strong>.";
                        } else {
                            $keteranganHtml = "Menambahkan data <strong>{$entitas}</strong> baru.";
                        }
                    } elseif (str_contains($aksi, 'HAPUS')) {
                        $detailParts = array_slice($parts, 4);
                        if (!empty($detailParts)) {
                            $keteranganHtml = "Menghapus data <strong>{$entitas}</strong>, rincian: <strong>" . htmlspecialchars(implode(' | ', $detailParts), ENT_QUOTES) . "</strong>.";
                        } else {
                            $keteranganHtml = "Menghapus data <strong>{$entitas}</strong>.";
                        }
                    } else {
                        // Fallback aksi lainnya
                        $keteranganHtml = "Melakukan <strong>{$aksi}</strong> pada <strong>{$entitas}</strong>. Rincian: " . htmlspecialchars($keteranganRaw, ENT_QUOTES);
                    }
                }

                $data[] = [
                    'waktu'      => $carbonDate ? $carbonDate->translatedFormat('d F Y, H:i:s') : $timeString,
                    'aksi'       => $aksi,
                    'user'       => $user,
                    'modul'      => $entitas,
                    'keterangan' => $keteranganHtml,
                    'raw'        => $line
                ];
            }
        }

        // Pagination Manual
        $total = count($data);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($data, $offset, $perPage);

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }

    /**
     * Membaca log login.
     */
    public function readLoginLog($search = null, $date = null, $perPage = 15, $page = 1)
    {
        $path = storage_path('logs/login.txt');
        if (!File::exists($path)) {
            return new LengthAwarePaginator([], 0, $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines); // LIFO

        $data = [];
        foreach ($lines as $line) {
            // Contoh baris: [2026-02-24 16:33:39] LOGIN FAIL — NIP tidak ditemukan: 28946137461982
            if (preg_match('/^\[(.*?)\] (.*?) — (.*)/', $line, $matches)) {
                $timeString = $matches[1];
                $status = $matches[2];
                $keterangan = $matches[3];

                try {
                    $carbonDate = Carbon::parse($timeString)->timezone('Asia/Jakarta');
                } catch (\Exception $e) {
                    $carbonDate = null;
                }

                // Filter Date
                if ($date && $carbonDate && $carbonDate->format('Y-m-d') !== $date) {
                    continue;
                }

                // Filter Search
                if ($search && stripos($line, $search) === false) {
                    continue;
                }

                $data[] = [
                    'waktu'      => $carbonDate ? $carbonDate->translatedFormat('d F Y, H:i:s') : $timeString,
                    'status'     => $status,
                    'keterangan' => $keterangan,
                    'raw'        => $line
                ];
            }
        }

        $total = count($data);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($data, $offset, $perPage);

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }
}
