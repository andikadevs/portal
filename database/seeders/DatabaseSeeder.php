<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Users: 1 ketua + 1 admin (kredensial dari .env) ---
        $ketua = User::updateOrCreate(
            ['email' => env('KETUA_EMAIL', 'ketua@newsportal.test')],
            [
                'name' => env('KETUA_NAME', 'Redaksi Utama'),
                'password' => Hash::make(env('KETUA_PASSWORD', 'password')),
                'role' => 'ketua',
                'email_verified_at' => now(),
            ],
        );

        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@newsportal.test')],
            [
                'name' => env('ADMIN_NAME', 'Editor Berita'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        $authors = collect([$ketua, $admin]);

        // --- Kategori dengan kode warna rubrik (PRD §7.2) ---
        $categoryDefs = [
            ['name' => 'Teknologi', 'color' => '#2E5BFF'],
            ['name' => 'Olahraga', 'color' => '#1B9C5D'],
            ['name' => 'Politik', 'color' => '#C8443B'],
            ['name' => 'Pendidikan', 'color' => '#D98A15'],
            ['name' => 'Ekonomi', 'color' => '#14315E'],
        ];

        $categories = collect($categoryDefs)->mapWithKeys(function (array $def): array {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($def['name'])],
                ['name' => $def['name'], 'color' => $def['color']],
            );

            return [$def['name'] => $category];
        });

        // --- Artikel kurasi (judul realistis) per kategori ---
        $curated = [
            'Teknologi' => [
                'Startup Lokal Luncurkan Asisten AI Berbahasa Indonesia',
                'Adopsi Kendaraan Listrik Melonjak di Kota-Kota Besar',
                'Regulasi Data Pribadi Mulai Diterapkan Tahun Ini',
            ],
            'Olahraga' => [
                'Timnas Melaju ke Final Setelah Laga Dramatis',
                'Atlet Muda Pecahkan Rekor Nasional Lari 100 Meter',
            ],
            'Politik' => [
                'Parlemen Sahkan Anggaran Infrastruktur Digital',
                'Pemerintah Daerah Dorong Transparansi Layanan Publik',
            ],
            'Pendidikan' => [
                'Kurikulum Baru Tekankan Literasi Digital Sejak Dini',
                'Beasiswa Riset Dibuka untuk Mahasiswa Vokasi',
            ],
            'Ekonomi' => [
                'UMKM Digital Catat Pertumbuhan Transaksi Signifikan',
                'Inflasi Terkendali, Daya Beli Masyarakat Membaik',
            ],
        ];

        foreach ($curated as $categoryName => $titles) {
            $category = $categories[$categoryName];

            foreach ($titles as $title) {
                Article::factory()
                    ->for($category)
                    ->for($authors->random(), 'author')
                    ->create([
                        'title' => $title,
                        'slug' => Str::slug($title),
                    ]);
            }
        }

        // --- Pastikan >=7 artikel di satu kategori (Teknologi) untuk uji pagination ---
        $teknologi = $categories['Teknologi'];
        $teknologiCount = $teknologi->articles()->count();

        if ($teknologiCount < 12) {
            Article::factory()
                ->count(12 - $teknologiCount)
                ->for($teknologi)
                ->for($authors->random(), 'author')
                ->create();
        }

        // --- Komentar contoh pada sebagian artikel ---
        Article::query()->inRandomOrder()->limit(6)->get()->each(function (Article $article): void {
            Comment::factory()->count(random_int(1, 4))->for($article)->create();
        });
    }
}
