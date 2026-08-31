<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'nama_lengkap' => fake()->name(),
            'role' => UserRole::Marketing,
            'status_user' => 'AKTIF',
            'remember_token' => Str::random(10),
        ];
    }

    public function marketing(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Marketing,
        ]);
    }

    public function atasanMarketing(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::AtasanMarketing,
        ]);
    }

    public function adminBackoffice(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::AdminBackoffice,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Admin,
        ]);
    }
}
