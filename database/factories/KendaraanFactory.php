<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kendaraan>
 */
class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
            'Toyota Avanza', 'Toyota Innova', 'Toyota Fortuner', 'Mitsubishi Pajero Sport',
            'Honda CR-V', 'Honda Brio', 'Suzuki Ertiga', 'Daihatsu Xenia',
            'Honda Supra X', 'Honda Vario', 'Yamaha NMAX', 'Yamaha Lexi'
        ];

        $plateLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $nopol = 'BD ' . $this->faker->numberBetween(1000, 9999) . ' ' . $this->faker->randomElement($plateLetters) . $this->faker->randomElement($plateLetters);

        return [
            'nama_kendaraan' => $this->faker->randomElement($brands),
            'no_polisi' => $nopol,
            'tahun' => $this->faker->year(),
            'no_rangka' => strtoupper($this->faker->unique()->bothify('*****************')), // 17 chars
            'no_mesin' => strtoupper($this->faker->unique()->bothify('??##########')),
            'pajak' => $this->faker->dateTimeBetween('-1 month', '+1 year')->format('Y-m-d'),
            'jenis_penggunaan' => $this->faker->randomElement(['jabatan', 'operasional']),
            'lokasi_operasional' => 'Bengkulu Kota',
            'kategori_id' => \App\Models\Kategori::factory(),
            'status' => 'aktif',
        ];
    }
}
