<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryIds = Category::pluck('id')->toArray();
        return [
            'Titre' => $this->faker->sentence,
            'Contenu' => $this->faker->paragraph,
            'Categorie_id' => $this->faker->randomElement($categoryIds),
            'Date_debut' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'Date_expiration' => $this->faker->dateTimeBetween('+1 month', '+2 month'),
        ];
    }
}
