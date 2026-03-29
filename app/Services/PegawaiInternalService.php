<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PegawaiInternalService
{
    /**
     * Fetch dummy data for an internal Pegawai by NIP.
     * 
     * @param string $nip
     * @return array|null
     */
    public function fetchPegawaiByNip(string $nip): ?array
    {
        try {
            // URL yang diberikan user
            $url = 'https://api-splp.layanan.go.id/t/bengkulukota.go.id/data_kinerja/1.0/api/pegawai/' . $nip . '/get_pegawai';
            
            $response = Http::withoutVerifying()
                ->retry(3, 1000)
                ->get($url);

            if ($response->successful()) {
                $raw = $response->json();
                
                // Ekstraksi data dari wrapper (data, content, atau root)
                $data = $raw['data'] ?? $raw['content'] ?? $raw;
                
                // Jika list, ambil item pertama
                if (isset($data[0]) && is_array($data[0])) {
                    $data = $data[0];
                }

                if (empty($data) || !is_array($data)) {
                    return null;
                }

                // Pemetaan sensitif huruf besar/kecil (untuk database pemerintah yang sering UPPERCASE)
                $nama = $data['nama_lengkap'] ?? $data['NAMA_LENGKAP'] ?? $data['nama'] ?? $data['NAMA'] ?? '—';
                $jabatan = $data['jabatan'] ?? $data['JABATAN'] ?? $data['nama_jabatan'] ?? $data['NAMA_JABATAN'] ?? '—';
                $opd = $data['nama_opd'] ?? $data['NAMA_OPD'] ?? $data['opd'] ?? $data['OPD'] ?? '—';

                // Jika masih strip, mungkin strukturnya berbeda, kita return dummy untuk sementara agar user bisa konfirmasi
                return [
                    'id'      => $data['id'] ?? $data['ID'] ?? null,
                    'nama'    => $nama,
                    'nip'     => $nip,
                    'jabatan' => $jabatan,
                    'opd'     => $opd,
                    'pangkat' => ($data['pangkat'] ?? $data['PANGKAT'] ?? '') . ' (' . ($data['golongan'] ?? $data['GOLONGAN'] ?? '') . '/' . ($data['ruang'] ?? $data['RUANG'] ?? '') . ')',
                ];
            }
        } catch (\Exception $e) {
             // Silahkan cek log laravel jika terjadi error koneksi
        }

        return null;
    }
}
