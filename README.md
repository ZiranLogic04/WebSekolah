# WebSekolah

Aplikasi website profil sekolah berbasis **Laravel 12 + Inertia.js + Vue.js 3** dengan panel admin lengkap.

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3-green?logo=vue.js)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple?logo=bootstrap)

---

## ✨ Fitur Utama

### 🌐 Website Publik
- **Beranda dinamis** - Slider, About, Counter, Program, Video, Team, Event, Testimonial, Partner
- **Halaman profil** - Visi Misi, Sejarah, Struktur Organisasi, Kepala Sekolah
- **Berita & Gallery** - Manajemen konten dengan CKEditor
- **Mega Menu** - Menu navigasi dinamis dengan sub-menu

### 🔧 Panel Admin
- **Dashboard** - Statistik dan quick access
- **Data Master** - Siswa, Guru, Kelas, Tahun Ajaran
- **Keuangan** - Jenis Tagihan, Tagihan Siswa, Transaksi, Rekap
- **Surat Menyurat** - Template surat, generate PDF
- **Tampilan Website** - Edit semua section beranda
- **Pengaturan** - Data Lembaga, Akun Admin

### 🛡️ Keamanan
- Autentikasi dengan Laravel
- Role-based access (Admin)
- CSRF protection

---

## 📋 Requirement

| Software | Versi |
|----------|-------|
| PHP | >= 8.2 |
| Composer | >= 2.0 |
| Node.js | >= 18 |
| MySQL/MariaDB | >= 8.0 |

---

## 🚀 Instalasi

### Quick Setup
```bash
git clone https://github.com/yourusername/WebSekolah.git
cd WebSekolah
composer setup
```

### Manual Setup
```bash
# 1. Clone & install dependencies
git clone https://github.com/yourusername/WebSekolah.git
cd WebSekolah
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Database (buat database 'websekolah' dulu)
php artisan migrate
php artisan db:seed

# 4. Storage link
php artisan storage:link

# 5. Build assets
npm run build
```

---

## 💻 Development

### Start Development Server
```bash
composer dev
```
Ini menjalankan Laravel server, Queue worker, dan Vite sekaligus.

### Manual Start
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

## 🔐 Login Admin

Setelah `php artisan db:seed`:

| Field | Value |
|-------|-------|
| Email | `admin@sekolah.com` |
| Password | `password` |

---

## 📦 Production

### Build & Optimize
```bash
composer optimize
```

### Clear Cache
```bash
composer optimize:clear
```

---

## 📁 Struktur Folder

```
WebSekolah/
├── app/
│   ├── Http/Controllers/     # Controllers
│   └── Models/               # Eloquent Models
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Data seeders
├── public/
│   └── assets/               # Static assets (CSS, JS, Images)
├── resources/
│   ├── js/
│   │   ├── Components/       # Vue components
│   │   ├── Layouts/          # Layout templates
│   │   └── Pages/            # Inertia pages
│   ├── css/                  # Custom CSS
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Route definitions
└── storage/
    └── app/public/           # User uploads
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Frontend | Vue.js 3 + Inertia.js |
| Styling | Bootstrap 5 |
| Bundler | Vite |
| Database | MySQL/MariaDB |
| PDF | mPDF |
| Excel | Maatwebsite Excel |
| Editor | CKEditor 5 |

---

## 📝 Changelog

### v1.0.0 (2024)
- Initial release
- Website profil sekolah
- Panel admin lengkap
- Manajemen keuangan
- Surat menyurat

---

## 📄 License

MIT License - Bebas digunakan untuk keperluan apapun.

---

## 🤝 Contributing

1. Fork repository
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

**Made with ❤️ by DiviaCode**
