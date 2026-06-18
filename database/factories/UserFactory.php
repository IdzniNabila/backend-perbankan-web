<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'password' => static::$password ??= Hash::make('password123'),
            'pin_hash' => Hash::make('123456'),
            'email' => fake()->unique()->safeEmail(),
            'nama_lengkap' => fake()->name(),
            'nomor_identitas' => fake()->unique()->numerify('##########'),
            'jenis_identitas' => 'NIM',
            'nomor_telepon' => fake()->numerify('08##########'),
            'alamat' => fake()->address(),
            'status' => 'aktif',
        ];
    }
}