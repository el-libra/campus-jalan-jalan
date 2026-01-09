<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Spot;
use App\Models\SpotPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $spots = [
            [
                'category' => 'gedung',
                'name' => 'Gedung Rektorat USH',
                'short_description' => 'Pusat administrasi utama universitas.',
                'description' => 'Gedung Rektorat adalah pusat layanan administrasi dan kepemimpinan universitas.',
                'latitude' => -6.200300,
                'longitude' => 106.816100,
                'address' => 'Komplek USH, Jalan Merdeka 1',
                'open_time' => '08:00',
                'close_time' => '17:00',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'akademik',
                'name' => 'Fakultas Teknik',
                'short_description' => 'Gedung perkuliahan dan laboratorium teknik.',
                'description' => 'Fakultas Teknik menyediakan ruang kuliah, laboratorium, dan ruang diskusi mahasiswa.',
                'latitude' => -6.200800,
                'longitude' => 106.816500,
                'address' => 'Komplek USH, Jalan Merdeka 2',
                'open_time' => '07:30',
                'close_time' => '18:00',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => false,
            ],
            [
                'category' => 'fasilitas',
                'name' => 'Perpustakaan Utama',
                'short_description' => 'Ruang baca dengan koleksi lengkap.',
                'description' => 'Perpustakaan utama menyediakan ruang baca nyaman dan akses jurnal digital.',
                'latitude' => -6.199900,
                'longitude' => 106.816900,
                'address' => 'Komplek USH, Jalan Merdeka 3',
                'open_time' => '08:00',
                'close_time' => '20:00',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'parkir',
                'name' => 'Area Parkir Timur',
                'short_description' => 'Parkir kendaraan roda dua dan empat.',
                'description' => 'Area parkir timur terletak dekat pintu masuk utama.',
                'latitude' => -6.201200,
                'longitude' => 106.816300,
                'address' => 'Komplek USH, Jalan Merdeka 4',
                'open_time' => '06:00',
                'close_time' => '22:00',
                'has_wifi' => false,
                'has_toilet' => false,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'layanan',
                'name' => 'Student Service Center',
                'short_description' => 'Layanan informasi dan bantuan mahasiswa.',
                'description' => 'SSC melayani konsultasi akademik, keuangan, dan informasi kampus.',
                'latitude' => -6.200600,
                'longitude' => 106.815700,
                'address' => 'Komplek USH, Jalan Merdeka 5',
                'open_time' => '08:00',
                'close_time' => '16:30',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'fasilitas',
                'name' => 'Auditorium Nusantara',
                'short_description' => 'Ruang acara dan seminar kampus.',
                'description' => 'Auditorium untuk seminar, wisuda, dan kegiatan besar kampus.',
                'latitude' => -6.201000,
                'longitude' => 106.817100,
                'address' => 'Komplek USH, Jalan Merdeka 6',
                'open_time' => '08:00',
                'close_time' => '21:00',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'akademik',
                'name' => 'Fakultas Ekonomi',
                'short_description' => 'Gedung perkuliahan ekonomi dan bisnis.',
                'description' => 'Fakultas Ekonomi menyediakan ruang kelas modern dan pusat studi bisnis.',
                'latitude' => -6.200100,
                'longitude' => 106.817400,
                'address' => 'Komplek USH, Jalan Merdeka 7',
                'open_time' => '07:30',
                'close_time' => '18:00',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => false,
            ],
            [
                'category' => 'gedung',
                'name' => 'Gedung Inovasi',
                'short_description' => 'Pusat riset dan inovasi mahasiswa.',
                'description' => 'Gedung inovasi memfasilitasi inkubasi startup dan riset terapan.',
                'latitude' => -6.199700,
                'longitude' => 106.816300,
                'address' => 'Komplek USH, Jalan Merdeka 8',
                'open_time' => '09:00',
                'close_time' => '19:00',
                'has_wifi' => true,
                'has_toilet' => false,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'fasilitas',
                'name' => 'Kantin Hijau',
                'short_description' => 'Area makan mahasiswa dengan banyak pilihan.',
                'description' => 'Kantin hijau menyediakan menu harian, kopi, dan area duduk terbuka.',
                'latitude' => -6.200400,
                'longitude' => 106.817900,
                'address' => 'Komplek USH, Jalan Merdeka 9',
                'open_time' => '07:00',
                'close_time' => '19:00',
                'has_wifi' => true,
                'has_toilet' => true,
                'is_wheelchair_accessible' => true,
            ],
            [
                'category' => 'layanan',
                'name' => 'Klinik Kampus',
                'short_description' => 'Layanan kesehatan untuk civitas.',
                'description' => 'Klinik kampus melayani pemeriksaan ringan dan konsultasi.',
                'latitude' => -6.201400,
                'longitude' => 106.816800,
                'address' => 'Komplek USH, Jalan Merdeka 10',
                'open_time' => '08:00',
                'close_time' => '17:00',
                'has_wifi' => false,
                'has_toilet' => true,
                'is_wheelchair_accessible' => true,
            ],
        ];

        foreach ($spots as $index => $spot) {
            $category = $categories[$spot['category']] ?? null;
            if (!$category) {
                continue;
            }

            $record = Spot::create([
                'category_id' => $category->id,
                'name' => $spot['name'],
                'slug' => Str::slug($spot['name']),
                'short_description' => $spot['short_description'],
                'description' => $spot['description'],
                'latitude' => $spot['latitude'],
                'longitude' => $spot['longitude'],
                'address' => $spot['address'],
                'open_time' => $spot['open_time'],
                'close_time' => $spot['close_time'],
                'has_wifi' => $spot['has_wifi'],
                'has_toilet' => $spot['has_toilet'],
                'is_wheelchair_accessible' => $spot['is_wheelchair_accessible'],
                'is_active' => true,
            ]);

            SpotPhoto::create([
                'spot_id' => $record->id,
                'photo_path' => 'https://picsum.photos/seed/ush-' . ($index + 1) . '/800/600',
            ]);
        }
    }
}
