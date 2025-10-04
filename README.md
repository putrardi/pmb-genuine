Sistem Pendaftaran Mahasiswa Baru (PMB)

Sistem PMB berbasis Laravel 12 dengan dukungan multi‐role (admin, staff, calon) mencakup: pendaftaran akun calon, wizard biodata & dokumen (storage privat), pemilihan gelombang & prodi, submit & verifikasi, dashboard analitik (Chart.js), notifikasi email (Mailtrap), serta master data (Prodi, Gelombang).

1) Tech Stack
Backend: PHP 8.2, Laravel 12, MySQL/MariaDB
Frontend: Blade + Tailwind CSS, Chart.js
Auth & Role: Laravel auth + middleware role:admin,staff,calon_mahasiswa
Storage: Dokumen di storage/app/private (non-public)
Mail: Laravel Mail → Mailtrap (Email Testing), queue-ready (sync/database)
Testing: PHPUnit/Pest — feature tests inti
Tools opsional: GitHub (PR/Issues/branching)

2) Fitur Utama
Landing: info gelombang aktif, daftar prodi & kuota, form daftar akun calon
Login pengguna: halaman khusus /login-user (admin/staff/calon yang sudah punya akun)
Dashboard Calon: stepper (Biodata → Dokumen → Gelombang & Prodi → Submit), lock setelah submit
Panel Verifikasi (Admin/Staff): list submitted, detail & preview dokumen privat, aksi Terima/Tolak + catatan
Dashboard Admin: grafik (by gender, by prodi, tren submit harian)
Master Data: Program Studi, Gelombang (validasi aktif & tidak overlap)
Notifikasi Email: saat submit & saat verifikasi (queue-ready)
Pendaftar (Admin/Staff): tabel pendaftar periode aktif, cari & filter status

3) Persyaratan Sistem
PHP 8.2
Composer 2.x
MySQL/MariaDB
Node.js (jika ingin rebuild asset Tailwind—opsional bila sudah tersedia)
Ekstensi PHP: openssl, pdo, mbstring, tokenizer, xml, ctype, json, fileinfo



4) Instalasi & Deployment
# clone
git clone <REPO_URL> pmb-ku
cd pmb-ku

# install dep
composer install

# salin env
cp .env.example .env

# generate key
php artisan key:generate

4.1 Konfigurasi .env minimal
APP_NAME="PMB Genuine"
APP_ENV=local
APP_KEY=base64:***     # hasil key:generate
APP_DEBUG=true
APP_URL=http://localhost/pmb-genuine/public  # sesuaikan environment kamu

# DB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pmb_ku
DB_USERNAME=root
DB_PASSWORD=

# Mail (Mailtrap sandbox)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=YOUR_MAILTRAP_USER
MAIL_PASSWORD=YOUR_MAILTRAP_PASS
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@pmb.test
MAIL_FROM_NAME="${APP_NAME}"

# Queue
QUEUE_CONNECTION=database   # atau sync jika tidak ingin queue beneran

4.2 Migrasi & Seeder (opsional) 
#Migration 
php artisan migrate
# php artisan migrate --seed    # jika tersedia seeder akun contoh/master
4.3 Storage Link (jika dibutuhkan oleh asset publik)
php artisan storage:link
4.4 Dependency
Npm install
Npm run dev
4.5 Queue (opsional)
# untuk gunakan DB queue
php artisan queue:table
php artisan migrate
php artisan queue:work
4.6 Jalankan
Via XAMPP/Apache: arahkan VirtualHost/alias ke public/


Via artisan (alternatif cepat):

 php artisan serve
 Lalu akses http://127.0.0.1:8000 (atau sesuai APP_URL).



5) Role & Akun
Calon: daftar akun dari / (landing)
Admin/Staff: login via /login-user
Redirect per role setelah login:
Admin/Staff → /admin/dashboard
Calon → /dashboard-calon
Akun seeder:
u: admin@pmb.test p: password123 (Admin)
u: staff@pmb.test p: password123 (Staff)


6) Rute Penting
GET  /                       -> Landing (calon baru)
POST /register-calon         -> Buat akun calon

GET  /login-user             -> Form login pengguna (admin/staff/calon)
POST /login                  -> Proses login (bawaan)

# Calon (auth + role:calon_mahasiswa)
GET  /dashboard-calon        -> Dashboard calon
GET  /calon/pilih-gelombang  -> Pilih gelombang & prodi
POST /calon/pilih-gelombang  -> Simpan pilihan
POST /calon/submit           -> Submit final

# Admin/Staff (auth + role:admin,staff)
GET  /admin                  -> Redirect ke /admin/dashboard
GET  /admin/dashboard        -> KPI & chart
GET  /admin/pendaftar        -> Tabel pendaftar periode aktif

# Admin saja (auth + role:admin)
GET  /admin/prodi            -> Master Prodi (CRUD)
GET  /admin/gelombang        -> Gelombang (CRUD)

# Verifikasi (admin/staff)
GET  /staff/verifikasi       -> List submitted
GET  /staff/verifikasi/{id}  -> Detail & preview dokumen
POST /staff/verifikasi/{id}/verify  -> Terima
POST /staff/verifikasi/{id}/reject  -> Tolak

Middleware role:* mengamankan akses per fitur.

7) Dokumen & Storage Privat
Upload KTP, Ijazah, Pas Foto disimpan di:
 storage/app/private/pendaftar/{user_id}/...
Akses preview melalui controller (stream) yang memeriksa auth + role.
Jangan letakkan file sensitif di public/.
config/filesystems.php (cuplikan disk privat):
'disks' => [
  'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'throw' => false,
  ],
],


8) Email (Mailtrap Sandbox)
.env menggunakan sandbox.smtp.mailtrap.io (Email Testing).


Email tidak dikirim ke penerima asli—hanya masuk ke inbox Mailtrap.
Untuk produksi, gunakan Mailtrap Email Sending (live.smtp.mailtrap.io) atau SMTP resmi dan gunakan FROM yang terverifikasi.
Pengiriman:
Sinkron: Mail::to(...)->send(new Mailable(...))
Queue (butuh worker): Mail::to(...)->queue(new Mailable(...))

9) Testing
Jalankan semua test:
php artisan test

Contoh kategori uji:
Feature: landing/register calon, submit final (guard kelengkapan), verifikasi staff.
Profile/misc test (opsional).

10) Struktur Direktori (ringkas)
app/
  Domain/
    Auth/Services/AuthService.php
    Master/Models/{ProgramStudi,Gelombang}.php
    Pendaftaran/Models/Pendaftaran.php
  Http/
    Controllers/
      LandingController.php
      Calon/{DashboardController,SubmitController,...}
      Admin/{AdminDashboardController,ProgramStudiController,...}
      Verifikasi/{VerifikasiController}.php
    Middleware/EnsureUserHasRole.php
    Requests/Calon/{...}
resources/
  views/{landing,calon,admin,staff,layouts,...}
database/
  migrations/*.php
routes/
  web.php


11) ERD (teks ringkas)
users: id, name, email, password, role


program_studi: id, kode (unik), nama, jenjang, kuota, aktif(bool)


gelombang_pendaftaran: id, nama, mulai(date), selesai(date), biaya(int), aktif(bool)


pendaftarans: id, user_id, no_reg, status(enum: draft/submitted/verified/rejected),
 biodata(JSON), dokumen(JSON), gelombang_id, prodi_id, submitted_at, verified_at, verified_by, verification_note


(Gambar ERD terdapat di docs/erd.png)

12) Keamanan Dasar
Password hash (bcrypt/argon)
CSRF token aktif
Role-based middleware pada rute terbatas
Dokumen sensitif di private storage
Validasi FormRequest pada input kritikal

13) Git Workflow (saran)
Branch: main (stabil), seeder (fixing)

15) Troubleshooting Umum
Undefined variable $slot
 → View menggunakan layout component (<x-guest-layout>) tetapi dipanggil dengan @extends. Ganti jadi:
 <x-guest-layout> ... </x-guest-layout>
RouteNotFoundException: route(...) not defined
 → Pastikan route terdaftar di routes/web.php, jalankan:

 php artisan route:clear
php artisan optimize:clear
Disk [private] not configured
 → Tambahkan disk private pada config/filesystems.php, php artisan config:clear.


Mailer “mailtrap” not defined
 → Gunakan MAIL_MAILER=smtp + host/port Mailtrap sandbox.


Login admin/staff tidak redirect ke /admin/dashboard
 → Pastikan override AuthenticatedSessionController@store() untuk redirect per role, dan route admin.dashboard ada.


Redis class not found (optimize:clear)
 → Jika tidak memakai Redis, abaikan atau set CACHE_DRIVER=file di .env.
