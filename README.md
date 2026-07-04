# NewsPortal — Portal Berita (Laravel 13)

Aplikasi web portal berita multi-role sesuai [PRD.md](PRD.md). Pengunjung membaca,
mencari, dan berkomentar; **Admin** mengelola kategori & artikel; **Ketua** mengelola
seluruh sistem termasuk user dan statistik.

## Stack

- **Laravel 13** · PHP 8.5 (min. 8.3)
- **MySQL 8**
- **Blade** + **Tailwind CSS v4** (Vite)
- **CKEditor 5** (rich text) · `mews/purifier` (sanitasi HTML)
- Autentikasi kustom (facade `Auth`) — bukan Breeze
- **Pexels** (opsional) — pilih thumbnail langsung dari Pexels di dasbor
- **Laravel Boost** (agentic dev tooling: guidelines, skills, MCP)

## Fitur (peta ke PRD F-01…F-15)

Homepage dinamis (lede + rail terbaru + seksi per kategori), detail artikel, kategori,
pencarian global & per-kategori, komentar publik, halaman Tentang, login multi-role,
dasbor admin & ketua, CRUD kategori/artikel/user, upload thumbnail, CKEditor, pagination,
kartu statistik, dan menu berbeda per role.

## Menjalankan secara lokal

Prasyarat: PHP 8.3+, Composer, Node.js 20+, MySQL 8.

```bash
# 1. Dependensi
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
# Sunting .env — set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3. Database (buat dulu: CREATE DATABASE portal_berita;)
php artisan migrate --seed
php artisan storage:link

# 4. Jalankan (dua terminal)
npm run dev
php artisan serve
```

Buka http://localhost:8000.

### Akun demo (dari seeder)

| Role  | Email                    | Password   |
|-------|--------------------------|------------|
| Ketua | ketua@newsportal.test    | `password` |
| Admin | admin@newsportal.test    | `password` |

> Kredensial seeder dapat diubah lewat variabel `KETUA_*` / `ADMIN_*` di `.env`.

## Testing

```bash
php artisan test
```

Mencakup autentikasi, otorisasi per-role, CRUD artikel (termasuk upload & sanitasi XSS),
komentar, dan pagination.

## Struktur penting

- `app/Http/Controllers` — controller publik & `Admin/*`
- `app/Http/Middleware/EnsureUserHasRole.php` — middleware `role:`
- `app/Models` — `User`, `Category`, `Article`, `Comment`
- `resources/views/components/layouts` — layout publik & dasbor
- `database/seeders/DatabaseSeeder.php` — data awal (≥7 artikel di kategori Teknologi)

## Deploy (Railway)

Lihat PRD §9. Set `APP_ENV=production`, `APP_KEY`, variabel `DB_*` dari service MySQL,
lalu release command:
`php artisan migrate --force && php artisan db:seed --force && php artisan storage:link`.
