<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KendaraanAktivitas>
 */
class KendaraanAktivitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activities = [
            'Service Rutin', 'Ganti Oli', 'Perbaikan Rem', 
            'Ganti Ban', 'Pengecekan Mesin', 'Cuci Kendaraan',
            'Perpanjangan STNK', 'Maintenance AC'
        ];

        return [
            'kendaraan_id' => \App\Models\Kendaraan::factory(),
            'judul_aktivitas' => $this->faker->randomElement($activities),
            'deskripsi' => $this->faker->sentence(),
            'tanggal_aktivitas' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'biaya_terpakai' => $this->faker->numberBetween(100000, 2000000),
            'created_by' => 1,
        ];
    }
}
