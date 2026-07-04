# PRD — Portal Berita "NewsPortal"
### Product Requirements Document · Laravel Edition

**Versi:** 1.0
**Tanggal:** 4 Juli 2026
**Mata Kuliah:** Pemrograman Web 2
**Basis requirement:** Modul Pertemuan 9–14 + Format Pengumpulan (15a)
**Metode pengumpulan target:** Deploy online (Railway) — Pilihan 3

---

## 1. Ringkasan Produk

NewsPortal adalah aplikasi web portal berita multi-role. Pengunjung membaca dan mencari berita serta berkomentar; Admin mengelola konten (kategori & artikel); Ketua mengelola seluruh sistem termasuk user dan melihat statistik. Produk ini adalah reimplementasi requirement Pertemuan 9–14 di atas framework **Laravel**, dengan UI editorial yang profesional (bukan tampilan template generik).

### 1.1 Tujuan
- Memenuhi 100% fitur yang tercantum di modul P9–P14 agar memenuhi kriteria nilai A ("seluruh fitur berjalan + tampilan bagus").
- Menghasilkan codebase yang rapi, aman, dan mudah di-maintain memanfaatkan konvensi Laravel.
- Deploy online di Railway dengan URL publik (bukan localhost).

### 1.2 Catatan asumsi (diambil karena tidak diatur eksplisit di modul)
- Modul aslinya menargetkan **PHP Native Prosedural**; PRD ini sengaja memakai **Laravel** atas permintaan. Karena framework tidak dilarang tertulis di dokumen tapi juga tidak eksplisit diperbolehkan, **disarankan konfirmasi ke dosen** sebelum submit final.
- Stack final: Laravel 12, PHP 8.2+, MySQL 8, Blade + Tailwind CSS (via Vite), CKEditor 5 untuk rich text.
- Role tetap dua sesuai modul: `ketua` dan `admin`. Pengunjung tidak butuh akun.

---

## 2. Lingkup (Scope)

### 2.1 In-scope (wajib, sesuai P9–14)
| Kode | Fitur | Sumber |
|------|-------|--------|
| F-01 | Homepage dinamis: hero/lede, berita terbaru, berita per kategori | P9, P12, P14 |
| F-02 | Halaman detail artikel | P12 |
| F-03 | Halaman kategori + daftar artikel per kategori | P13 |
| F-04 | Pencarian artikel | P13 |
| F-05 | Sistem komentar publik pada artikel | P13 |
| F-06 | Halaman "Tentang" / profil penerbit | P12 |
| F-07 | Login multi-role (ketua & admin) + logout | P10 |
| F-08 | Dashboard Admin | P11 |
| F-09 | CRUD Kategori | P11 |
| F-10 | CRUD Artikel + upload thumbnail | P11 |
| F-11 | Dashboard Ketua + kartu statistik | P14 |
| F-12 | CRUD User (khusus ketua) | P14 |
| F-13 | Pagination daftar artikel | P14 |
| F-14 | Rich Text Editor pada form artikel | P14 |
| F-15 | Menu berbeda antara role ketua vs admin | P14 |

### 2.2 Out-of-scope (tidak diminta modul)
Registrasi publik, reset password via email, komentar berjenjang/reply, like/share, notifikasi, API eksternal, moderasi komentar (komentar langsung tampil sesuai modul P13).

---

## 3. Persona & Hak Akses

| Peran | Autentikasi | Kemampuan |
|-------|-------------|-----------|
| **Pengunjung** | Tidak login | Baca berita, cari, filter kategori, kirim komentar, lihat halaman tentang |
| **Admin** | Login | Dashboard, kelola kategori, kelola artikel (miliknya/semua), logout |
| **Ketua** | Login | Semua akses admin + kelola user + statistik penuh |

**Matriks otorisasi** diterapkan lewat middleware `role:` dan Gate/Policy Laravel:
- `/dashboard`, `/kategori/*`, `/artikel/*` → `role:ketua,admin`
- `/users/*`, `/statistik` → `role:ketua`

---

## 4. Arsitektur & Tech Stack

| Layer | Pilihan | Alasan |
|-------|---------|--------|
| Framework | Laravel 12 (PHP 8.2+) | Diminta; konvensi rapi, keamanan bawaan |
| Auth scaffold | Laravel Breeze (Blade stack) | Ringan, Blade+Tailwind, mudah dikustom untuk multi-role |
| Database | MySQL 8 | Sesuai modul |
| View | Blade + komponen | Server-rendered, cocok untuk konten |
| Styling | Tailwind CSS + Vite | Kontrol penuh atas desain (menghindari tampilan template) |
| Rich text | CKEditor 5 | Kontinuitas dengan modul P14 |
| Deploy | Railway (auto-detect Laravel, php-fpm + Caddy) | Diminta |

### 4.1 Keputusan penting Laravel vs modul native
- Koneksi DB: tidak pakai `mysqli_connect` manual, gunakan Eloquent + konfigurasi `.env` (`DB_*`).
- Password: `Hash::make()` / bcrypt bawaan (menggantikan `password_hash()`).
- Proteksi SQL Injection: query builder/Eloquent memakai prepared statement otomatis.
- Proteksi XSS: Blade `{{ }}` auto-escape; konten dari CKEditor disanitasi (mis. `mews/purifier`) sebelum ditampilkan dengan `{!! !!}`.
- CSRF: token `@csrf` otomatis pada semua form.
- Validasi: `FormRequest` menggantikan `trim()`/`htmlspecialchars()` manual.

---

## 5. Model Data (Migrations)

Empat entitas inti sesuai modul, disesuaikan konvensi Laravel (timestamps, foreign key).

**users**
```
id, name, email (unique), password, role ENUM('ketua','admin'),
email_verified_at (nullable), remember_token, timestamps
```

**categories** (kategori)
```
id, name, slug (unique), timestamps
```

**articles** (artikel)
```
id, title, slug (unique), excerpt (nullable), body LONGTEXT,
thumbnail (nullable, path), category_id FK->categories,
user_id FK->users, published_at, timestamps
```

**comments** (komentar)
```
id, article_id FK->articles (cascade delete), name, email,
body TEXT, timestamps
```

**Relasi Eloquent**
- `Article belongsTo Category`, `Article belongsTo User (author)`, `Article hasMany Comment`
- `Category hasMany Article`
- `User hasMany Article`
- `Comment belongsTo Article`

**Seeder wajib:** 1 user `ketua` + 1 user `admin` (kredensial dari `.env`), beberapa kategori, dan **minimal 7 artikel dalam satu kategori** agar pagination terbukti bekerja saat demo.

---

## 6. Spesifikasi Fungsional Detail

### F-07 Autentikasi Multi-Role
- Route: `GET/POST /login`, `POST /logout` (Breeze).
- Login pakai email + password, verifikasi via `Auth::attempt`.
- Setelah login, redirect ke `/dashboard`; sidebar & menu dirender sesuai `auth()->user()->role`.
- Middleware `role` custom melindungi seluruh area admin.
- Kriteria terima: user salah password ditolak dengan pesan jelas; halaman admin tidak bisa diakses tanpa login (redirect ke `/login`).

### F-08 / F-11 Dashboard
- Admin melihat ringkasan: jumlah artikel, jumlah kategori, artikel terbaru miliknya.
- Ketua melihat semua di atas **plus** kartu statistik: total user, total kategori, total artikel, total komentar (query `count()` per model).
- Kriteria terima: angka statistik akurat mengikuti data DB.

### F-09 CRUD Kategori
- Route resource `categories` (index, create, store, edit, update, destroy).
- Slug otomatis dari nama (`Str::slug`).
- Validasi: nama wajib, unik.
- Kriteria terima: tambah/edit/hapus kategori langsung terlihat di daftar dan memengaruhi filter frontend.

### F-10 CRUD Artikel + Upload
- Route resource `articles`.
- Form: judul, kategori (select), excerpt, body (CKEditor), thumbnail (file).
- Upload disimpan ke `storage/app/public/thumbnails`, diakses via `storage:link`. Validasi mime (jpg/png/webp) & ukuran maks (mis. 2MB).
- Slug otomatis dari judul; `user_id` = penulis yang login.
- Kriteria terima: artikel tampil di frontend lengkap dengan thumbnail, kategori, dan nama penulis.

### F-12 CRUD User (Ketua)
- Route resource `users` dilindungi `role:ketua`.
- Field: nama, email, password (hash saat simpan), role.
- Kriteria terima: ketua bisa menambah admin baru yang langsung bisa login.

### F-01 Homepage Dinamis
- **Lede/hero**: 1 artikel terbaru ditonjolkan besar (thumbnail, kategori, judul, ringkasan, penulis, waktu).
- **Berita terbaru**: daftar artikel urut `published_at` desc.
- **Per kategori**: beberapa artikel per kategori + link "Lihat semua".
- Hanya sebagian artikel di home (sisanya via halaman kategori/pagination — sesuai P14).

### F-02 Detail Artikel
- Route `GET /berita/{slug}`.
- Menampilkan judul, dateline (kategori + tanggal), penulis, thumbnail, body (HTML dari editor, sudah disanitasi), lalu form + daftar komentar.

### F-03 / F-04 Kategori & Pencarian
- Route `GET /kategori/{slug}` dengan dukungan query `?q=` untuk cari dalam kategori.
- Route `GET /cari?q=` untuk pencarian global (judul/isi).
- Hasil dipaginasi (F-13, `->paginate(9)`), UI pagination Tailwind kustom.

### F-05 Komentar
- `POST /berita/{slug}/komentar`.
- Validasi via FormRequest: nama, email (format email), isi wajib.
- Sanitasi otomatis (escape saat render). Komentar tampil urut terbaru di bawah artikel.

### F-06 Halaman Tentang
- Route `GET /tentang`, konten statis profil penerbit + kredibilitas redaksi.

---

## 7. Sistem Desain UI/UX

> Tujuan: tampilan **editorial newsroom modern** yang terlihat sengaja dirancang — bukan "AI slop". Arah desain sengaja menghindari tiga default umum (cream + serif kontras tinggi + aksen terracotta; latar hitam + aksen neon; broadsheet hairline generik).

### 7.1 Konsep
Identitas: *"digital newsroom yang tenang tapi tegas"*. Elemen dunia berita (dateline, kicker/eyebrow, kode warna rubrik, jam terbit) dijadikan bahasa desain, bukan hiasan.

### 7.2 Token Warna
| Token | Hex | Peran |
|-------|-----|-------|
| `--ink` | `#17191E` | Teks utama, masthead |
| `--paper` | `#FBFAF7` | Latar halaman (warm off-white, sengaja bukan cream #F4F1EA) |
| `--brand` | `#14315E` | Navy editorial — identitas & tautan penting (kesan kredibel) |
| `--action` | `#2E5BFF` | Tombol/aksi interaktif |
| `--signal` | `#C8443B` | Penanda "Terkini"/breaking & CTA primer — dipakai sangat hemat |
| `--muted` | `#6B7280` | Metadata, caption |
| `--line` | `#E7E4DD` | Garis pemisah halus |

Kode warna rubrik (dipakai pada label kategori — ini "signature" yang membawa informasi nyata): tiap kategori punya satu warna konsisten (mis. Teknologi biru, Olahraga hijau, Politik merah bata, Pendidikan amber).

### 7.3 Tipografi (deliberate pairing)
- **Display/masthead:** `Archivo` (berat 700–800, tracking rapat) untuk judul & wordmark — grotesque, bukan serif kontras-tinggi default.
- **Body baca artikel:** `Spectral` (serif) — nyaman untuk long-form reading.
- **Utility/UI:** `Inter` — metadata, tombol, tabel dashboard, caption.
- Skala tipe jelas: masthead 32–40px, H1 artikel 34px, body 18px/1.7, metadata 13px uppercase tracking lebar.

### 7.4 Layout kunci
```
MASTHEAD:  [WORDMARK]        [dateline: Jumat, 4 Jul 2026]   [cari]
NAVBAR:    Beranda · Teknologi · Olahraga · Politik · Pendidikan · Tentang   [Login]
------------------------------------------------------------------
HOME:
┌───────────────────────────────┬───────────────┐
│  LEDE (hero) — 1 berita utama  │  TERBARU rail  │
│  thumbnail besar + kicker      │  3–4 headline  │
│  + judul besar + ringkasan     │  ringkas       │
├───────────────────────────────┴───────────────┤
│  SEKSI PER KATEGORI (grid kartu 3 kolom)        │
└─────────────────────────────────────────────────┘

DETAIL: kicker(kategori•tanggal) → H1 → byline → thumbnail →
        body (measure ~680px, serif) → komentar

ADMIN:  [sidebar role-aware] + [topbar] + [tabel/form area]
```

### 7.5 Komponen
- **Kartu berita:** thumbnail rasio tetap (16:9), label kategori berwarna, judul (Archivo), meta penulis•waktu (Inter). Tanpa bayangan berlebihan; andalkan garis `--line` dan spasi.
- **Kicker/eyebrow:** label kecil uppercase di atas judul = kategori + waktu relatif ("2 jam lalu").
- **Tombol:** primer solid `--action`, sekunder outline `--ink`. Label = kata kerja aktif ("Kirim komentar", "Simpan artikel", "Publikasikan").
- **Pagination:** angka + prev/next, state aktif kontras jelas.
- **Dashboard cards:** angka besar Archivo + label kecil; garis kiri berwarna sesuai metrik.

### 7.6 Bahasa antarmuka (copy)
Sesuai prinsip: aktif, sentence case, spesifik. Contoh: tombol "Publikasikan" → toast "Artikel dipublikasikan". Empty state daftar artikel: "Belum ada artikel. Tambah artikel pertamamu." Error validasi: jelas apa yang salah & cara memperbaikinya, bukan sekadar "gagal".

### 7.7 Quality floor
Responsive sampai mobile (nav jadi menu ringkas, grid jadi 1 kolom), focus keyboard terlihat, `prefers-reduced-motion` dihormati, kontras teks memenuhi WCAG AA. Motion hemat: hanya reveal halus saat scroll pada kartu & transisi hover — jangan berlebihan (justru bikin terasa AI-generated).

---

## 8. Kebutuhan Non-Fungsional

| Aspek | Ketentuan |
|-------|-----------|
| Keamanan | CSRF di semua form; validasi FormRequest; Eloquent (anti-SQLi); Blade escaping (anti-XSS); sanitasi HTML editor; password bcrypt; otorisasi via middleware/Gate |
| Performa | Eager loading (`with()`) hindari N+1; pagination; index pada `slug`, `category_id`, `published_at` |
| Aksesibilitas | Alt text thumbnail, label form, kontras AA, navigasi keyboard |
| Responsif | Breakpoint mobile/tablet/desktop |
| Maintainability | Struktur MVC standar, Form Requests, Blade components, seeder & migration lengkap |

---

## 9. Deployment (Railway)

**Prasyarat:** aplikasi sudah jalan sempurna di lokal, repo sudah di GitHub, akun Railway diverifikasi via GitHub (akun trial belum terverifikasi punya akses jaringan keluar terbatas). Kredit trial $5 cukup untuk masa penilaian.

**Langkah:**
1. New Project → Deploy from GitHub repo (Railway auto-detect Laravel → php-fpm + Caddy).
2. New → Database → Add MySQL.
3. Variables service Laravel:
   ```
   APP_ENV=production
   APP_KEY=(hasil php artisan key:generate --show)
   APP_URL=https://<subdomain>.up.railway.app
   FORCE_HTTPS=true
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   ```
4. Force HTTPS di `AppServiceProvider::boot()` saat `environment('production')`.
5. Release/start command jalankan migrasi + seed + storage link:
   `php artisan migrate --force && php artisan db:seed --force && php artisan storage:link`
6. Settings → Networking → Generate Domain.

**Gotcha filesystem ephemeral:** thumbnail yang di-upload akan hilang saat redeploy. Solusi: pasang **Railway Volume** yang di-mount ke direktori storage upload, atau (paling aman untuk demo) upload seluruh artikel setelah deploy final dan jangan redeploy sampai selesai dinilai. Untuk jangka panjang: pindah media ke object storage S3-compatible.

---

## 10. Kriteria Terima (Peta ke Nilai A)

Nilai A = "seluruh fitur berjalan + tampilan bagus". Definisi selesai:
- [ ] Semua F-01…F-15 berfungsi di URL online (bukan localhost).
- [ ] Login ketua & admin berhasil; menu keduanya berbeda.
- [ ] CRUD kategori, artikel, user tuntas; upload thumbnail tampil.
- [ ] Home, detail, kategori, pencarian, komentar, tentang berjalan.
- [ ] Pagination terlihat bekerja (≥7 artikel/kategori).
- [ ] CKEditor aktif di form artikel; format tersimpan & tampil.
- [ ] Statistik dashboard ketua akurat.
- [ ] Tampilan konsisten dengan sistem desain Bab 7, responsif di mobile.
- [ ] `Projek.rar` + `.sql`/seeder di Drive dengan akses "siapa saja yang punya link".
- [ ] Link web online + link Drive dilampirkan di CBT.

---

## 11. Peta Rute (ringkas)

```
GET  /                         Home
GET  /berita/{slug}            Detail artikel
POST /berita/{slug}/komentar   Kirim komentar
GET  /kategori/{slug}          Artikel per kategori (+ ?q= cari dalam kategori)
GET  /cari                     Pencarian global (?q=)
GET  /tentang                  Halaman tentang
GET  /login  POST /login       Auth
POST /logout                   Logout
--- middleware role:ketua,admin ---
GET  /dashboard                Dashboard (konten sesuai role)
resource /kategori (categories)
resource /artikel  (articles)
--- middleware role:ketua ---
resource /users
GET  /statistik
```

---

## 12. Urutan Build (disarankan, mepet waktu)

1. `laravel new`, install Breeze (Blade), Tailwind, koneksi DB lokal.
2. Migration + model + relasi + seeder (user, kategori, ≥7 artikel).
3. Middleware `role` + proteksi area admin + menu role-aware.
4. CRUD kategori → CRUD artikel + upload → CRUD user.
5. Frontend: home → detail → kategori/cari → komentar → tentang.
6. Dashboard + statistik + pagination + CKEditor.
7. Terapkan sistem desain (Bab 7) menyeluruh + QA responsif.
8. Push GitHub → deploy Railway → import/seed → uji URL online.
9. Rar + upload Drive → lampirkan link di CBT (buka tugas CBT hanya saat semua siap; 1x kesempatan, tanpa perpanjangan).

---

*Catatan akhir: modul asli menargetkan PHP Native. Pastikan penggunaan Laravel sudah dikonfirmasi ke dosen sebelum submit final.*