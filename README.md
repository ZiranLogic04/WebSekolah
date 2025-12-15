# WebSekolah

Aplikasi website sekolah berbasis **Laravel 12 + Inertia.js + Vue.js 3**.

## Fitur

- 🏫 Website profil sekolah (beranda, profil, visi-misi, struktur organisasi)
- 📰 Manajemen berita/post
- 👨‍🎓 Data siswa dan kelas
- 💰 Manajemen keuangan (tagihan, pembayaran, transaksi)
- 📄 Surat menyurat
- ⚙️ Pengaturan data lembaga

## Requirement

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL / MariaDB

## Instalasi

### Cara Cepat

```bash
git clone https://github.com/ZiranLogic04/WebSekolah.git
cd WebSekolah
composer setup
```

### Cara Manual

```bash
# Clone repository
git clone https://github.com/ZiranLogic04/WebSekolah.git
cd WebSekolah

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat database 'websekolah' di MySQL, lalu jalankan migrasi
php artisan migrate

# Seed data awal (user admin, data lembaga, template surat)
php artisan db:seed

# Buat symlink storage
php artisan storage:link

# Build assets
npm run build
```

## Development

Jalankan server development dengan satu perintah:

```bash
composer dev
```

Ini akan menjalankan **Laravel server**, **Queue worker**, dan **Vite** secara bersamaan.

Atau jalankan terpisah:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

## Login Admin

Setelah menjalankan `php artisan db:seed`, gunakan kredensial berikut:

- **Email**: `admin@sekolah.com`
- **Password**: `password`

## Struktur Folder

```
├── app/                    # Logic aplikasi (Controllers, Models)
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Data seeder
├── public/                 # Public assets
├── resources/
│   ├── js/                 # Vue.js components & pages
│   └── views/              # Blade templates
├── routes/                 # Route definitions
└── storage/                # File uploads & cache
```

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3 + Inertia.js
- **Bundler**: Vite
- **Database**: MySQL/MariaDB
- **Styling**: Bootstrap (dari template)

## License

MIT License
