<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías ficticias
        Category::create([
            'name' => 'Electronics',
            'description' => 'Devices, gadgets, and accessories.',
        ]);

        Category::create([
            'name' => 'Books',
            'description' => 'Various genres of books.',
        ]);

        Category::create([
            'name' => 'Clothing',
            'description' => 'Apparel and accessories.',
        ]);

    }
}
