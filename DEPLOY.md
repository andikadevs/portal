# Deploy ke Railway

Panduan men-deploy **NewsPortal** ke [Railway](https://railway.app). Ringkasan: build
di-handle Nixpacks (otomatis mendeteksi Laravel + Vite), migrasi & optimize dijalankan
lewat **preDeploy command** ([railway.json](railway.json)), data awal di-seed sekali
secara manual.

## Prasyarat
- Aplikasi sudah berjalan sempurna di lokal (`php artisan test` hijau).
- Repo sudah di-push ke GitHub.
- Akun Railway terverifikasi (login via GitHub).

## Langkah

### 1. Buat project + database
1. Railway → **New Project** → **Deploy from GitHub repo** → pilih repo ini.
   Railway mendeteksi Laravel via Nixpacks (PHP + Caddy + build aset Vite otomatis).
2. **New** → **Database** → **Add MySQL**.

### 2. Set Variables (service Laravel)
Buka service Laravel → tab **Variables** → paste isi [.env.production.example](.env.production.example).
Yang wajib diperhatikan:
- `APP_KEY` → jalankan `php artisan key:generate --show` di lokal, salin hasilnya.
- `APP_URL` → isi setelah domain dibuat (langkah 4), lalu redeploy.
- `DB_*` → biarkan memakai referensi `${{MySQL.*}}` (Railway mengisi otomatis).
- `PEXELS_API_KEY` → isi bila ingin fitur pemilih thumbnail Pexels aktif (opsional).
- `KETUA_PASSWORD` / `ADMIN_PASSWORD` → ganti dengan kata sandi kuat.

### 3. Deploy
Railway otomatis build & deploy. Urutan yang terjadi:
- **Build:** `composer install` + `npm run build` (aset Vite).
- **preDeploy** (dari `railway.json`): `migrate --force` → `storage:link` →
  `config:cache` → `route:cache` → `view:cache`.
- **Start:** Caddy menyajikan `public/`.

Health check: Railway memantau `/up` (endpoint bawaan Laravel).

### 4. Domain + seed data awal
1. Service Laravel → **Settings** → **Networking** → **Generate Domain**.
2. Update variabel `APP_URL` dengan domain tersebut → redeploy.
3. Seed data demo **sekali** (jangan diulang tiap deploy — artikel akan dobel):
   ```bash
   # via Railway CLI (railway login && railway link)
   railway run php artisan db:seed --force
   ```
   atau buka service → **Shell** dan jalankan `php artisan db:seed --force`.

Selesai. Buka domain untuk cek. Login redaksi: email & kata sandi sesuai variabel
`KETUA_*` / `ADMIN_*`.

## Catatan penting

**Filesystem ephemeral.** Thumbnail yang di-upload manual tersimpan di
`storage/app/public` dan akan **hilang saat redeploy**. Solusi:
- Pakai **pemilih Pexels** untuk thumbnail (URL eksternal, tidak terpengaruh redeploy) — direkomendasikan; atau
- Pasang **Railway Volume** di-mount ke `storage/app/public`; atau
- Pindahkan media ke object storage S3 (`FILESYSTEM_DISK=s3`).
Data seeder sudah memakai URL Pexels sehingga aman lintas redeploy.

**Jika CSS/JS tidak muncul** setelah deploy: pastikan build Vite berjalan. Di
service **Settings → Build**, set Build Command bila perlu:
`npm ci && npm run build && composer install --no-dev --optimize-autoloader`.

**HTTPS.** `AppServiceProvider::boot()` memaksa skema HTTPS saat `APP_ENV=production`,
dan proxy Railway sudah dipercaya (`trustProxies` di `bootstrap/app.php`).

**Ganti konfigurasi env.** Setelah mengubah Variables, Railway otomatis redeploy;
`config:cache` di preDeploy memastikan nilai terbaru terpakai.
