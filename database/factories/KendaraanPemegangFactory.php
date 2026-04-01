<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KendaraanPemegang>
 */
class KendaraanPemegangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Budi Santoso', 'Siti Aminah', 'Agus Prayitno', 'Lestari Putri',
            'Adi Wijaya', 'Dewi Sartika', 'Iwan Setiawan', 'Ani Suryani'
        ];
        
        $ranks = [
            'Polda Bengkulu' => ['AKBP', 'Kompol', 'AKP', 'Iptu', 'Ipda', 'Aiptu', 'Aipda'],
            'UNIB' => ['Prof.', 'Dr.', 'Dekan', 'Dosen', 'Staf Administrasi']
        ];

        $unitNames = ['Polda Bengkulu', 'Universitas Bengkulu (UNIB)'];
        $selectedUnit = $this->faker->randomElement($unitNames);
        $rank = $this->faker->randomElement($ranks[$selectedUnit === 'Polda Bengkulu' ? 'Polda Bengkulu' : 'UNIB']);
        $name = $rank . ' ' . $this->faker->randomElement($names);

        $tanggalSk = $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');

        return [
            'kendaraan_id' => \App\Models\Kendaraan::factory(),
            'source_system' => 'Manual',
            'nip' => $this->faker->numerify('##################'), // 18 digits 
            'nama_pegawai' => $name,
            'jabatan_pegawai' => $rank . ' ' . $this->faker->word(),
            'unit_pegawai' => $selectedUnit,
            'pegawai_id' => null,
            'nomor_sk' => $this->faker->numerify('800/###/SK/2024'),
            'tanggal_sk' => $tanggalSk,
            'tanggal_mulai' => $tanggalSk, // Added missing column
            'is_active' => true,
        ];
    }
}
