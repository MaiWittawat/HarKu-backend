<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserInfoFactory extends Factory
{
    private $nextUserId = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id' => $this->nextUserId++, // สุ่ม user_id จากรายการ ID ของผู้ใช้
            'birthday' => '2001-5-2',
            'prefer_min_age' => rand(18,30),
            'prefer_max_age' => rand(31,100),
            'height' => rand(150,250),
            'gender' => ['man', 'woman', 'other'][array_rand(['man', 'woman', 'other'])],
            'show_gender' => ['man', 'woman', 'all'][array_rand(['man', 'woman', 'all'])],
            'relation' => ['Marriage', 'A serious relationship', 'Something casual', 'Prefer not ot say', 'Not sure yet']
                          [array_rand(['Marriage', 'A serious relationship', 'Something casual', 'Prefer not ot say', 'Not sure yet'])],
            'education' => fake()->name(),
            'smoking' => ['Smoker', 'Non-smoker'][array_rand(['Smoker', 'Non-smoker'])],
            'drinking' => ['Sober curious', 'Socially', 'Never drink'][array_rand(['Sober curious', 'Socially', 'Never drink'])],
            'about_me' => fake()->name(),
            'latitude' => rand(10,15),
            'longitude' => rand(96, 105),
            'distance' => rand(1, 20)
        ];
    }
}
