<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\Kendaraan;
use App\Models\KendaraanPemegang;
use App\Models\QrKendaraan;
use App\Models\KendaraanAktivitas;
use App\Models\User;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Ensure Admin user exists
        $admin = User::first();
        if (!$admin) {
            $this->call(AdminSeeder::class);
            $admin = User::first();
        }

        // 1. Create Categories
        $kat4 = Kategori::updateOrCreate(['nama_kategori' => 'Roda 4 (Mobil)']);
        $kat2 = Kategori::updateOrCreate(['nama_kategori' => 'Roda 2 (Motor)']);
        $kat6 = Kategori::updateOrCreate(['nama_kategori' => 'Roda 6 (Truck/Bus)']);

        // 2. Create Units & SubUnits
        $units = [
            'Polda Bengkulu' => ['Ditlantas', 'Ditreskrim', 'Satbrimob', 'Yanduan'],
            'Universitas Bengkulu (UNIB)' => ['Fakultas Teknik', 'Fakultas Kedokteran', 'Rektorat', 'Fakultas Pertanian']
        ];

        foreach ($units as $unitName => $subUnits) {
            $unit = Unit::updateOrCreate(['nama_unit' => $unitName]);
            foreach ($subUnits as $subUnitName) {
                SubUnit::updateOrCreate([
                    'unit_id' => $unit->id,
                    'nama_sub_unit' => $subUnitName
                ]);
            }
        }

        // 3. Create Vehicles
        $brands = [
            'Roda 4 (Mobil)' => ['Toyota Avanza', 'Toyota Innova', 'Mitsubishi Pajero Sport', 'Toyota Fortuner'],
            'Roda 2 (Motor)' => ['Honda Supra X', 'Honda Vario', 'Yamaha NMAX', 'Honda Beat'],
            'Roda 6 (Truck/Bus)' => ['Hino Dutro', 'Mitsubishi Fuso', 'Isuzu Giga']
        ];

        for ($i = 0; $i < 15; $i++) {
            $kategori = rand(0, 10) > 7 ? $kat2 : (rand(0, 10) > 9 ? $kat6 : $kat4);
            $brand = $brands[$kategori->nama_kategori][array_rand($brands[$kategori->nama_kategori])];
            
            $plateLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $nopol = 'BD ' . rand(1000, 9999) . ' ' . $plateLetters[array_rand($plateLetters)] . $plateLetters[array_rand($plateLetters)];

            $kendaraan = Kendaraan::create([
                'nama_kendaraan' => $brand,
                'no_polisi' => $nopol,
                'tahun' => rand(2018, 2024),
                'no_rangka' => strtoupper(Str::random(17)),
                'no_mesin' => strtoupper(Str::random(12)),
                'pajak' => now()->addDays(rand(-20, 365)),
                'jenis_penggunaan' => rand(0, 1) ? 'jabatan' : 'operasional',
                'lokasi_operasional' => 'Bengkulu Kota',
                'kategori_id' => $kategori->id,
                'status' => 'aktif',
            ]);

            // 4. Create QR Code
            QrKendaraan::create([
                'kendaraan_id' => $kendaraan->id,
                'token' => strtoupper(Str::random(9)),
                'scan_count' => rand(5, 50),
            ]);

            // 5. Create Holder (Pemegang)
            $selectedUnitKey = array_rand($units);
            $ranks = $selectedUnitKey === 'Universitas Bengkulu (UNIB)' 
                ? ['Prof.', 'Dr.', 'Dekan', 'Dosen'] 
                : ['AKBP', 'Kompol', 'AKP', 'Iptu', 'Ipda'];
            
            $names = ['Budi', 'Siti', 'Agus', 'Lestari', 'Adi', 'Dewi', 'Iwan', 'Ani'];
            $tanggalSk = now()->subMonths(rand(1, 12));
            
            KendaraanPemegang::create([
                'kendaraan_id' => $kendaraan->id,
                'source_system' => 'Manual',
                'nip' => rand(1980, 2000) . rand(10, 12) . rand(10, 28) . rand(2005, 2024) . rand(0, 1) . rand(100, 999),
                'nama_pegawai' => $ranks[array_rand($ranks)] . ' ' . $names[array_rand($names)],
                'jabatan_pegawai' => $ranks[array_rand($ranks)] . ' ' . Str::random(5),
                'unit_pegawai' => $selectedUnitKey,
                'nomor_sk' => '800/' . rand(100, 999) . '/SK/' . now()->year,
                'tanggal_sk' => $tanggalSk,
                'tanggal_mulai' => $tanggalSk, // Added missing column
                'is_active' => true,
            ]);

            // 6. Create Maintenance Activities
            $activityTitles = ['Service Rutin', 'Ganti Oli', 'Perbaikan Rem', 'Ganti Ban', 'Pengecekan Berkala'];
            $activityCount = rand(1, 3);
            for ($j = 0; $j < $activityCount; $j++) {
                KendaraanAktivitas::create([
                    'kendaraan_id' => $kendaraan->id,
                    'judul_aktivitas' => $activityTitles[array_rand($activityTitles)],
                    'deskripsi' => 'Maintenance rutin untuk menjaga performa kendaraan operasional.',
                    'tanggal_aktivitas' => now()->subDays(rand(1, 180)),
                    'biaya_terpakai' => rand(150, 1500) * 1000,
                    'created_by' => $admin->id,
                ]);
            }
        }
    }
}
