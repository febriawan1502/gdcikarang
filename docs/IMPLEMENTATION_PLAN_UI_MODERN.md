# Modernisasi UI Gudang Pojok

Upgrade tampilan aplikasi dari Bootstrap/jQuery ke **Tailwind CSS + Livewire 3** dengan design modern.

> **Tanggal dibuat**: 20 Januari 2026  
> **Status**: Draft - Menunggu approval

---

## Strategi Upgrade

> [!IMPORTANT]
> Pendekatan **incremental** (bukan rewrite total) untuk meminimalisir risiko dan memungkinkan rollback.

> [!WARNING]
> Layout baru akan berjalan paralel dengan layout lama. Views akan dimigrasi satu per satu.

---

## Referensi Design

![Referensi Dashboard](docs/ui-reference.png)

### Karakteristik Target:
- **Sidebar**: Clean, icon-based, collapsible
- **Cards**: Rounded corners, soft shadows, gradient accents
- **Charts**: Bar chart, donut chart, line chart
- **Color Scheme**: Purple/Blue cohesive theme
- **Typography**: Modern sans-serif (Inter/Poppins)

---

## Phase 1: Setup Modern Stack

### File yang Dibuat/Diubah:

| File | Aksi | Deskripsi |
|------|------|-----------|
| `tailwind.config.js` | NEW | Konfigurasi Tailwind dengan custom color palette |
| `vite.config.js` | MODIFY | Tambahkan Tailwind CSS processing |
| `composer.json` | MODIFY | Tambah `livewire/livewire: ^3.0` |
| `package.json` | MODIFY | Tambah `tailwindcss: ^3.4`, `@tailwindcss/forms: ^0.5` |
| `resources/css/app.css` | NEW | Entry point Tailwind dengan custom utilities |

### Color Palette:
```javascript
colors: {
  primary: { 50: '#EEF2FF', 500: '#6366F1', 600: '#4F46E5' },
  accent: { 500: '#F97316' },
  surface: { DEFAULT: '#F8FAFC', card: '#FFFFFF' }
}
```

---

## Phase 2: Redesign Layout

### Struktur Layout Baru:
```
┌─────────────────────────────────────────────────┐
│ Header (sticky, search, user dropdown)          │
├──────────┬──────────────────────────────────────┤
│          │                                      │
│ Sidebar  │   Main Content Area                  │
│ (fixed)  │   - Breadcrumb                       │
│          │   - Page Content                     │
│          │                                      │
└──────────┴──────────────────────────────────────┘
```

### File yang Dibuat:

| File | Deskripsi |
|------|-----------|
| `resources/views/layouts/modern.blade.php` | Layout utama baru |
| `resources/views/components/modern-sidebar.blade.php` | Sidebar dengan icon-based navigation |
| `resources/views/components/modern-header.blade.php` | Header dengan search & user dropdown |

---

## Phase 3: Dashboard Redesign

### Struktur Dashboard:
```
┌────────────┬────────────┬────────────┬────────────┐
│ Total Matl │ Total Stok │ Mat. Masuk │ Surat Jln  │  ← Stat Cards
└────────────┴────────────┴────────────┴────────────┘

┌─────────────────────────────┬───────────────────────┐
│   Bar Chart                 │   Donut Chart         │  ← Charts
│   (Material Movement)       │   (Stock Distribution)│
└─────────────────────────────┴───────────────────────┘

┌─────────────────────────────┬───────────────────────┐
│   Fast Moving Materials     │   Material Saving     │  ← Info Cards
└─────────────────────────────┴───────────────────────┘

┌─────────────────────────────┬───────────────────────┐
│   10 Surat Jalan Terakhir   │   10 Material Masuk   │  ← Recent Activity
└─────────────────────────────┴───────────────────────┘
```

### File yang Dibuat:

| File | Deskripsi |
|------|-----------|
| `app/Livewire/Dashboard.php` | Livewire component |
| `resources/views/livewire/dashboard.blade.php` | Dashboard view |

---

## Phase 4: Migrasi Views

### Data Material
| File Lama | File Baru (Livewire) |
|-----------|---------------------|
| `material/index.blade.php` | `livewire/material-list.blade.php` |
| `material/create.blade.php` | `livewire/material-form.blade.php` |

### Surat Jalan
| File Lama | File Baru (Livewire) |
|-----------|---------------------|
| `material/surat-jalan.blade.php` | `livewire/surat-jalan-list.blade.php` |
| `material/surat-jalan-create.blade.php` | `livewire/surat-jalan-form.blade.php` |

### Material Masuk
| File Lama | File Baru (Livewire) |
|-----------|---------------------|
| `material/material-masuk.blade.php` | `livewire/material-masuk-list.blade.php` |

---

## Phase 5: Laravel Upgrade (Optional)

> [!NOTE]
> Upgrade Laravel dilakukan setelah UI stabil untuk menghindari breaking changes ganda.

### Steps:
1. Upgrade `laravel/framework` 10 → 11
2. Update config files sesuai Laravel 11 structure
3. Test semua fitur
4. Upgrade 11 → 12
5. Final testing

---

## Timeline Estimasi

| Phase | Durasi | Catatan |
|-------|--------|---------|
| Phase 1 | 1-2 hari | Setup & config |
| Phase 2 | 2-3 hari | Layout redesign |
| Phase 3 | 3-4 hari | Dashboard + charts |
| Phase 4 | 5-7 hari | Migrasi semua views |
| Phase 5 | 2-3 hari | Laravel upgrade |
| **Total** | **~2-3 minggu** | Santai pace |

---

## Verification Checklist

- [ ] Tailwind CSS build berhasil
- [ ] Livewire components berfungsi
- [ ] Sidebar collapsible berfungsi
- [ ] Stat cards menampilkan data benar
- [ ] Charts render dengan data real
- [ ] Tables responsive di mobile
- [ ] Role-based menu visibility

---

## Rollback Strategy

Jika ada masalah:
1. Layout lama (`layouts/app.blade.php`) tetap ada
2. Views lama tidak dihapus, hanya deprecated
3. Bisa switch kembali dengan mengubah `@extends('layouts.modern')` → `@extends('layouts.app')`

---

## Commands Reference

```bash
# Setup branch untuk development
git checkout -b feature/ui-tailwind-livewire

# Install dependencies
composer require livewire/livewire
npm install tailwindcss @tailwindcss/forms

# Build assets
npm run build

# Run dev server
php artisan serve
npm run dev
```
