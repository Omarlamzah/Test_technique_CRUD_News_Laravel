<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->create(['nom' => 'Actualités']);
        Category::factory()->create(['nom' => 'Divertissement']);
        Category::factory()->create(['nom' => 'Technologie']);
        Category::factory()->create(['nom' => 'Santé']);
    }
}
