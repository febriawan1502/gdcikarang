# ASI System - Automatic System Inventory

## Deskripsi

ASI (Automatic System Inventory) adalah sistem manajemen gudang berbasis web yang dibangun menggunakan Laravel 10 dan Bootstrap 5. Sistem ini dirancang untuk mengelola inventori material dengan fitur-fitur modern dan antarmuka yang user-friendly.

## Fitur Utama

### ✅ Sudah Diimplementasi

- **Autentikasi & Otorisasi**

  - Login/Logout dengan validasi
  - Role-based access control (Admin & Petugas)
  - Change Password dengan validasi keamanan
  - Session management

- **Dashboard**

  - Statistik real-time material
  - DataTables dengan fitur pencarian, sorting, dan pagination
  - Action buttons (View, Edit, Delete)
  - Responsive design

- **Master Material**

  - CRUD operations lengkap
  - Form validation
  - Auto-fill functionality
  - Status management (Aktif/Tidak Aktif)
  - Stock tracking

- **Database & Models**
  - PostgreSQL support (primary)
  - MySQL support (alternative)
  - Eloquent relationships
  - Database migrations & seeders
  - Soft deletes

### 🚧 Template Tersedia (Siap Dikembangkan)

- **Input Material Masuk** - Template form sudah tersedia
- **Surat Jalan** - Template form sudah tersedia
- **Stock Opname** - Template halaman sudah tersedia

### 📋 Rencana Pengembangan

- User Management (Admin only)
- Reports & Analytics
- Export/Import functionality
- Barcode/QR Code integration
- Email notifications
- API endpoints

## Teknologi yang Digunakan

### Backend

- **Laravel 10** - PHP Framework
- **PHP 8.4** - Programming Language
- **PostgreSQL** - Primary Database
- **MySQL** - Alternative Database

### Frontend

- **Bootstrap 5.3** - CSS Framework
- **jQuery 3.7** - JavaScript Library
- **DataTables** - Table Enhancement
- **Font Awesome 6** - Icons
- **SweetAlert2** - Beautiful Alerts
- **Chart.js** - Charts & Graphs

### Build Tools

- **Vite 4** - Frontend Build Tool
- **Sass** - CSS Preprocessor
- **NPM** - Package Manager

## Persyaratan Sistem

### Minimum Requirements

- **PHP**: 8.4 atau lebih tinggi
- **Composer**: 2.0 atau lebih tinggi
- **Node.js**: 16.0 atau lebih tinggi
- **NPM**: 8.0 atau lebih tinggi
- **Database**: PostgreSQL 12+ atau MySQL 8.0+

### Ekstensi PHP yang Diperlukan

- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- pgsql PHP Extension (untuk PostgreSQL)
- mysql PHP Extension (untuk MySQL)

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd asi-apps
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` dan sesuaikan konfigurasi database:

#### Untuk PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=asi_system
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

#### Untuk MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asi_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Database Migration & Seeding

```bash
# Run migrations and seeders
php artisan migrate --seed

# Create storage link
php artisan storage:link
```

### 6. Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 7. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (in another terminal)
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

## Quick Setup (Development)

Untuk setup cepat development environment:

```bash
composer run-script setup-dev
```

Script ini akan menjalankan:

- `composer install`
- `php artisan key:generate`
- `php artisan migrate --seed`
- `php artisan storage:link`
- `npm install`
- `npm run build`

## Akun Default

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

### Administrator

- **Email**: admin@asi.com
- **Password**: admin123
- **Role**: Admin (Full Access)

### Petugas

- **Email**: petugas@asi.com
- **Password**: petugas123
- **Role**: Petugas (Limited Access)

### Test Users

- **Email**: user1@asi.com, user2@asi.com, user3@asi.com
- **Password**: password
- **Role**: Petugas

## Struktur Proyek

```
asi-apps/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   └── MaterialController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── Material.php
│       └── User.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   └── 2024_01_01_000001_create_materials_table.php
│   └── seeders/
│       ├── UserSeeder.php
│       ├── MaterialSeeder.php
│       └── DatabaseSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── change-password.blade.php
│       ├── dashboard/
│       │   ├── index.blade.php
│       │   └── stock-opname.blade.php
│       └── material/
│           ├── create.blade.php
│           ├── edit.blade.php
│           ├── input-masuk.blade.php
│           └── surat-jalan.blade.php
└── routes/
    └── web.php
```

## Penggunaan

### 1. Login

- Akses `http://localhost:8000`
- Gunakan salah satu akun default di atas
- Sistem akan redirect ke dashboard setelah login berhasil

### 2. Dashboard

- Lihat statistik material (Total, Aktif, Tidak Aktif, Stok Rendah)
- Kelola material melalui DataTable
- Gunakan fitur pencarian, filter, dan sorting

### 3. Material Management

- **Tambah Material**: Klik "Tambah Material" di dashboard
- **Edit Material**: Klik tombol "Edit" pada baris material
- **Hapus Material**: Klik tombol "Hapus" (dengan konfirmasi)
- **Lihat Detail**: Klik tombol "Detail" untuk melihat informasi lengkap

### 4. Role & Permissions

- **Admin**: Full access ke semua fitur
- **Petugas**: Access terbatas (sesuai konfigurasi)

## Development

### Running Tests

```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage
```

### Code Style

```bash
# Fix code style
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Asset Development

```bash
# Watch for changes (development)
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

### Database Operations

```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

## Troubleshooting

### Common Issues

1. **Permission Denied Errors**

   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **Database Connection Error**

   - Pastikan database service berjalan
   - Periksa konfigurasi `.env`
   - Test koneksi: `php artisan tinker` → `DB::connection()->getPdo()`

3. **Asset Not Loading**

   ```bash
   npm run build
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Composer Dependencies**
   ```bash
   composer dump-autoload
   composer install --no-dev --optimize-autoloader
   ```

### Performance Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

Project ini menggunakan [MIT License](LICENSE).

## Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

1. Periksa [Issues](../../issues) yang sudah ada
2. Buat [Issue baru](../../issues/new) jika diperlukan
3. Hubungi tim development: dev@asi-system.com

## Changelog

### Version 1.0.0 (Current)

- ✅ Initial release
- ✅ Authentication & Authorization
- ✅ Dashboard with statistics
- ✅ Material CRUD operations
- ✅ Role-based access control
- ✅ Responsive design
- ✅ Database migrations & seeders
- 🚧 Input Material Masuk (Template)
- 🚧 Surat Jalan (Template)
- 🚧 Stock Opname (Template)

---

**ASI System** - Automatic System Inventory  
Developed with ❤️ by ASI Development Team
