<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Get all category IDs from the database
        $categoryIds = Category::pluck('id')->toArray();

        return [
            'nom' => $this->faker->sentence,
            'category_id' => $this->faker->randomElement($categoryIds),
            // You can add more attributes here if necessary
        ];
    }
}
