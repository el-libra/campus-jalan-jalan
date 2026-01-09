<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin USH',
            'email' => 'admin@ush.test',
        ]);

        $this->call([
            CategorySeeder::class,
            SpotSeeder::class,
            TourSeeder::class,
        ]);
    }
}
