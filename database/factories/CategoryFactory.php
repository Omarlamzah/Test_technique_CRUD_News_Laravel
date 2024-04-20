<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->unique()->word,
            'parent_id' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Category $category) {
            $childrenData = [
                'Actualités' => ['Politique', 'Économie', 'Sport'],
                'Divertissement' => ['Cinéma', 'Musique', 'Sorties'],
                'Technologie' => [
                    'Informatique' => ['Ordinateurs de bureau', 'PC portable', 'Connexion internet'],
                    'Gadgets' => ['Smartphones', 'Tablettes', 'Jeux vidéo'],
                ],
                'Santé' => ['Médecine', 'Bien-être'],
            ];

            if (isset($childrenData[$category->nom])) {
                foreach ($childrenData[$category->nom] as $childName => $subCategories) {
                    // Check if the current childName is an array
                    if (is_array($subCategories)) {

                        $childCategory = Category::factory()->create([
                            'nom' => $childName,
                            'parent_id' => $category->id,
                        ]);

                        // Create subcategories
                        foreach ($subCategories as $subChildName) {
                            Category::factory()->create([
                                'nom' => $subChildName,
                                'parent_id' => $childCategory->id,
                            ]);
                        }
                    }
                    else {
                        // category it's not an array
                        Category::factory()->create([
                            'nom' => $subCategories,
                            'parent_id' => $category->id,
                        ]);
                    }
                }
            }
        });
    }



}
