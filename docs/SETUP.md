# Panduan Setup Campus Tour USH (Laravel 12)

Panduan ini menjelaskan proses pembuatan aplikasi dari 0 sampai kondisi website seperti sekarang, termasuk penjelasannya.

## 0) Prasyarat
- PHP 8.3+, Composer, Node.js 20+, MySQL
- API Key Google Maps JavaScript API (aktifkan di Google Cloud)
- Laragon vhost: `campus-tour-ush.test`

## 1) Inisialisasi Project
Tujuan: membuat project Laravel 12 baru.
```bash
composer create-project laravel/laravel campus-tour-ush "12.*"
cd campus-tour-ush
```

## 2) Konfigurasi Environment
Tujuan: menghubungkan app ke database dan menyiapkan env variabel.
Edit `.env`:
```env
APP_NAME="Campus Tour USH"
APP_URL=http://campus-tour-ush.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_tour
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
VITE_GOOGLE_MAPS_KEY=YOUR_KEY
```

Generate app key:
```bash
php artisan key:generate
```

## 3) Install Breeze (Auth Sederhana)
Tujuan: menyediakan login/register dan layout admin dasar.
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
```

## 4) Struktur Database (Migrations)
Tujuan: menyiapkan tabel utama sesuai kebutuhan aplikasi.
```bash
php artisan make:model Category -m
php artisan make:model Spot -m
php artisan make:model SpotPhoto -m
php artisan make:model Tour -m
php artisan make:model TourSpot -m
```

Skema inti:
- `categories`: name, slug, icon
- `spots`: category_id, name, slug, short_description, description, latitude, longitude, address, open_time, close_time, has_wifi, has_toilet, is_wheelchair_accessible, is_active
- `spot_photos`: spot_id, photo_path
- `tours`: name, slug, description
- `tour_spots`: tour_id, spot_id, order_index

## 5) Model & Relationship
Tujuan: relasi data konsisten di Eloquent.
- Category hasMany Spot
- Spot belongsTo Category, hasMany SpotPhoto, belongsToMany Tour (pivot `tour_spots`)
- Tour belongsToMany Spot
- TourSpot belongsTo Tour dan Spot

## 6) Routing Publik + Admin
Tujuan: memisahkan halaman publik dengan dashboard admin.
`routes/web.php`:
- `/` landing page map
- `/spots/{slug}` detail spot
- `/tour` guided tour
- `/admin/*` CRUD (auth)

## 7) Controller Publik
Tujuan: menyiapkan data untuk landing, detail, dan tour.
- `LandingController@index`: load kategori + spot aktif + foto
- `SpotController@show`: detail spot + related
- `TourController@index`: data tur + spot urutan

## 8) Controller Admin (CRUD)
Tujuan: panel admin untuk input data.
- `Admin/CategoryController`: CRUD kategori
- `Admin/SpotController`: CRUD spot + upload multiple foto
- `Admin/TourController`: CRUD tur + urutan spot

## 9) Storage Link
Tujuan: agar file upload bisa diakses publik.
```bash
php artisan storage:link
```

## 10) Blade UI Publik
Tujuan: tampilan utama map, detail spot, dan tour.
Lokasi view:
- `resources/views/landing/index.blade.php`
- `resources/views/spots/show.blade.php`
- `resources/views/tour/index.blade.php`

Fitur utama:
- Satellite view + marker per kategori
- List spot + filter + search
- Preview card saat marker diklik
- Guided tour dengan step-by-step + polyline

## 11) Blade UI Admin
Tujuan: dashboard admin rapi dan konsisten.
Lokasi view:
- `resources/views/admin/categories/*`
- `resources/views/admin/spots/*`
- `resources/views/admin/tours/*`

## 12) Integrasi Google Maps JS API
Tujuan: menampilkan peta satelit dan marker.
```html
<script src="https://maps.googleapis.com/maps/api/js?key=KEY&callback=initMap" defer></script>
```

## 13) Marker Custom + UX
Tujuan: marker lebih natural dan sesuai branding.
- Marker custom SVG per kategori
- Bottom sheet responsive + animasi
- Preview card dengan reveal animation

## 14) Seeder Data Contoh
Tujuan: ada data awal untuk demo.
```bash
php artisan make:seeder CategorySeeder
php artisan make:seeder SpotSeeder
php artisan make:seeder TourSeeder
```

Seed `DatabaseSeeder` agar otomatis memanggil seeder di atas.

## 15) Migrate + Seed
```bash
php artisan migrate --seed
```
Jika urutan foreign key bermasalah:
```bash
php artisan migrate:fresh --seed
```

## 16) Tema UI & Branding
Tujuan: tema natural campus + biru logo USH.
- CSS variables di `resources/css/app.css`
- Font: Sora + DM Serif Display
- Background bernuansa campus

Build asset:
```bash
npm run build
```

## 17) Logo Login Admin
Tujuan: mengganti logo default Breeze dengan logo USH.
- Simpan logo di `public/images/ush-logo.png`
- Komponen logo: `resources/views/components/application-logo.blade.php`

## 18) Jalankan Aplikasi
```bash
php artisan serve
```

URL:
- Landing: `/`
- Detail spot: `/spots/{slug}`
- Tour: `/tour`
- Admin: `/admin/*` (login dulu)

Login default (seed):
- `admin@ush.test`
- password: `password`

## 19) Mengganti Link & Koordinat Google Maps
Tujuan: menyesuaikan peta dengan lokasi kampus asli.

### A. Ganti API Key Google Maps
1. Buka `.env`
2. Isi:
```env
VITE_GOOGLE_MAPS_KEY=KEY_ASLI
```
3. (Opsional) Bersihkan cache config:
```bash
php artisan config:clear
```

### B. Ganti Titik Pusat Peta (Center)
1. Buka `resources/views/landing/index.blade.php`
2. Cari `defaultCenter` di fungsi `initMap()`:
```js
const defaultCenter = { lat: -6.200000, lng: 106.816666 };
```
3. Ganti dengan koordinat kampus.

1. Buka `resources/views/tour/index.blade.php`
2. Cari `center` di fungsi `initTourMap()`:
```js
center: { lat: -6.200000, lng: 106.816666 },
```
3. Ganti dengan koordinat kampus.

### C. Ganti Link "Buka di Google Maps"
Jika ingin link ke lokasi kampus/tautan share tertentu:
1. Landing page:
   - File `resources/views/landing/index.blade.php`
   - Cari tombol “Buka di Google Maps” dan ubah `href`.
2. Detail spot:
   - File `resources/views/spots/show.blade.php`
   - Cari tombol “Buka di Google Maps” dan ubah `href`.

Contoh link share Google Maps:
```
https://maps.google.com/?q=-6.200000,106.816666
```

## 20) Membuat Google Maps JavaScript API Key
Tujuan: mendapatkan API key agar Google Maps tampil tanpa watermark.

Langkah-langkah:
1. Buka Google Cloud Console: https://console.cloud.google.com/
2. Buat atau pilih Project.
3. Aktifkan Billing.
4. Enable API:
   - Menu **APIs & Services → Library**
   - Cari **Maps JavaScript API** → **Enable**
5. Buat API Key:
   - Menu **APIs & Services → Credentials**
   - **Create Credentials → API key**
6. Restrict API Key (disarankan):
   - **Application restrictions** → HTTP referrers
   - Tambahkan:
     - `http://127.0.0.1/*`
     - `http://localhost/*`
     - `http://campus-tour-ush.test/*`
   - **API restrictions** → Restrict → pilih **Maps JavaScript API**
7. Isi key di `.env`:
```env
VITE_GOOGLE_MAPS_KEY=AIzaSyXXXXXXXXXXXXXX
```
8. Clear cache config:
```bash
php artisan config:clear
```
