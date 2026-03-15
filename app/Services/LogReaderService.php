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
        $month = now()->format('Y_m');
        $path = storage_path("logs/aktivitas/aktivitas_{$month}.txt");
        if (!File::exists($path)) {
            return new LengthAwarePaginator([], 0, $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines); // LIFO (Terbaru di atas)

        $data = [];
        foreach ($lines as $line) {
            if (preg_match('/^\[(.*?)\] (.*)/', $line, $matches)) {
                $timeString = $matches[1];
                $messageStr = $matches[2];
                
                try {
                    $carbonDate = Carbon::parse($timeString)->timezone('Asia/Jakarta');
                } catch (\Exception $e) {
                    $carbonDate = null;
                }

                if ($date && $carbonDate && $carbonDate->format('Y-m-d') !== $date) {
                    continue;
                }

                if ($search && stripos($line, $search) === false) {
                    continue;
                }

                $parts = explode(' — ', $messageStr);
                $aksi = $parts[0] ?? '-';
                $user = isset($parts[1]) ? str_replace('User: ', '', $parts[1]) : '-';
                $entitas = isset($parts[2]) ? str_replace('Entitas: ', '', $parts[2]) : '-';
                
                // Pada format baru, $parts[3] adalah kalimat natural (keterangan HTML bisa dioverride dari format text langsung)
                // Jika data lama ditemukan dengan format ID: N, diabaikan dan pakai $parts[4] dll if necessary
                $keteranganHtml = '';
                if (count($parts) > 3) {
                    $keteranganRaw = implode(' — ', array_slice($parts, 3));
                    // Apabila data lama (karena transisi file):
                    if (str_starts_with($keteranganRaw, 'ID:')) {
                       $keteranganHtml = preg_replace('/ID:\s*\d+\s*(—\s*)?/', '', $keteranganRaw);
                       $keteranganHtml = str_replace([' | ', '|'], ', ', $keteranganHtml);
                    } else {
                       $keteranganHtml = $keteranganRaw; 
                    }
                }

                $keteranganHtml = htmlspecialchars($keteranganHtml, ENT_QUOTES);

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
        $month = now()->format('Y_m');
        $path = storage_path("logs/login/login_{$month}.txt");
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
