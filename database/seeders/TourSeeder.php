<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tours = [
            [
                'name' => 'Tur Umum Kampus',
                'description' => 'Pengantar kampus dan fasilitas utama.',
                'spots' => [
                    'gedung-rektorat-ush',
                    'perpustakaan-utama',
                    'auditorium-nusantara',
                    'kantin-hijau',
                ],
            ],
            [
                'name' => 'Tur Mahasiswa Baru',
                'description' => 'Rute orientasi mahasiswa baru.',
                'spots' => [
                    'student-service-center',
                    'fakultas-teknik',
                    'perpustakaan-utama',
                    'area-parkir-timur',
                ],
            ],
            [
                'name' => 'Tur Akademik',
                'description' => 'Menjelajahi area akademik utama.',
                'spots' => [
                    'fakultas-teknik',
                    'fakultas-ekonomi',
                    'gedung-inovasi',
                ],
            ],
        ];

        foreach ($tours as $tourData) {
            $tour = Tour::create([
                'name' => $tourData['name'],
                'slug' => Str::slug($tourData['name']),
                'description' => $tourData['description'],
            ]);

            foreach ($tourData['spots'] as $index => $spotSlug) {
                $spot = Spot::where('slug', $spotSlug)->first();
                if (!$spot) {
                    continue;
                }
                $tour->spots()->attach($spot->id, ['order_index' => $index + 1]);
            }
        }
    }
}
