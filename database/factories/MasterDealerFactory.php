<?php

namespace Database\Factories;

use App\Models\MasterDealer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MasterDealer>
 */
class MasterDealerFactory extends Factory
{
    protected $model = MasterDealer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_dealer' => fake()->company().' Motor',
            'alamat' => fake()->address(),
        ];
    }
}
