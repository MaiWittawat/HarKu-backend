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
            'birthday' => '01-01-2500',
            'age' => rand(18,80),
            'height' => rand(150,250),
            'gender' => ['man', 'woman', 'other'][array_rand(['man', 'woman', 'other'])],
            'show_gender' => ['man', 'woman', 'all'][array_rand(['man', 'woman', 'all'])],
            'relation' => ['Marriage', 'A serious relationship', 'Something casual', 'Prefer not ot say', 'Not sure yet']
                          [array_rand(['Marriage', 'A serious relationship', 'Something casual', 'Prefer not ot say', 'Not sure yet'])],
            'education' => fake()->name(),
            'smoking' => ['smoking', 'Non-smoking'][array_rand(['Smoker', 'Non-smoker'])],
            'drinking' => ['Sober curious', 'Socially', 'Nerver drink'][array_rand(['Sober curious', 'Socially', 'Nerver drink'])],
            'about_me' => fake()->name(),
            'first_date_idea' => fake()->name(),
        ];
    }
}
