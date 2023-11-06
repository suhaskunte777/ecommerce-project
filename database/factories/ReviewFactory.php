<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createDate = $this->faker->dateTimeBetween('-1 year', 'now');
        return [
            'rating' => floor($this->faker->randomFloat(1, 0, 5) * 2) / 2,
            'comment' => $this->faker->paragraph(),
            'created_at' => $createDate,
            'updated_at' => $createDate,
        ];
    }
}
