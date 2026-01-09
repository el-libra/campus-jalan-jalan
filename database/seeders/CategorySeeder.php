<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Gedung', 'icon' => 'building'],
            ['name' => 'Fasilitas', 'icon' => 'star'],
            ['name' => 'Parkir', 'icon' => 'parking'],
            ['name' => 'Akademik', 'icon' => 'book'],
            ['name' => 'Layanan', 'icon' => 'service'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
            ]);
        }
    }
}
