<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_unit' => $this->faker->unique()->randomElement([
                'Polda Bengkulu',
                'Universitas Bengkulu (UNIB)',
                'Polres Bengkulu Kota',
                'Polres Rejang Lebong'
            ]),
        ];
    }
}
