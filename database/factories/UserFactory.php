<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'birthday' => '01-01-2500',
            'gender' => ['man', 'woman', 'other'][array_rand(['man', 'woman', 'other'])],
            'show_gender' => ['man', 'woman', 'all'][array_rand(['man', 'woman', 'all'])],
            'goals' => ['Marriage', 'A serious relationship', 'Something casual', 'Prefer not ot say', 'Not sure yet']
                          [array_rand(['Marriage', 'A serious relationship', 'Something casual', 'Prefer not ot say', 'Not sure yet'])]

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
