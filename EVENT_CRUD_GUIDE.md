# Panduan Event CRUD System

## 📋 Ringkasan Fitur

Sistem Event CRUD yang telah dikembangkan mencakup:

1. **CRUD Event** - Create, Read, Update, Delete events
2. **Upload Banner** - Upload gambar banner untuk event
3. **Kategori Event** - Mengelola kategori event
4. **Jadwal & Lokasi** - Mengatur waktu dan tempat event
5. **Kuota Tiket** - Mengatur jumlah tiket yang tersedia

---

## 🗄️ Database Structure

### Table: `acara`

```
event_id (VARCHAR, PRIMARY KEY)
organizer_id (BIGINT UNSIGNED, FOREIGN KEY)
category_id (BIGINT UNSIGNED, NULLABLE, FOREIGN KEY)
name (VARCHAR 200)
description (VARCHAR 1000)
location (VARCHAR 255)
schedule_time (DATETIME)
ticket_quota (INT)
banner_url (VARCHAR, NULLABLE)
status (ENUM: draft, published, cancelled)
```

### Table: `kategori_acara`

```
category_id (BIGINT UNSIGNED, PRIMARY KEY, AUTO INCREMENT)
name (VARCHAR 100)
description (TEXT, NULLABLE)
icon (VARCHAR 50, NULLABLE)
color (VARCHAR 7, DEFAULT: #3B82F6)
is_active (BOOLEAN, DEFAULT: 1)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### Table: `ticket_type`

```
id (BIGINT UNSIGNED, PRIMARY KEY, AUTO INCREMENT)
event_id (VARCHAR, FOREIGN KEY)
name (VARCHAR 255)
price (DECIMAL)
quantity_total (INT)
quantity_sold (INT)
```

---

## 📁 File yang Diperbarui

### Controllers

- **[EventController.php](app/Http/Controllers/EventController.php)** - CRUD operations untuk events
- **[EventCategoryController.php](app/Http/Controllers/Admin/EventCategoryController.php)** - CRUD untuk kategori

### Views

- **[events/create.blade.php](resources/views/events/create.blade.php)** - Form buat event baru
- **[events/edit.blade.php](resources/views/events/edit.blade.php)** - Form edit event
- **[events/index.blade.php](resources/views/events/index.blade.php)** - Daftar event dipublikasi
- **[events/show.blade.php](resources/views/events/show.blade.php)** - Detail event

### Models

- **[Event.php](app/Models/Event.php)** - Model Event (sudah ada, compatible)
- **[EventCategory.php](app/Models/EventCategory.php)** - Model EventCategory

---

## 🚀 Cara Menggunakan

### 1. Membuat Event Baru (Organizer)

**Route:** `POST /organizer/events` atau akses dari `/events/create`

**Langkah-langkah:**

1. Login sebagai Organizer atau Admin
2. Klik "Buat Event Baru"
3. Isi form dengan detail:
    - **Judul Event** (max 200 karakter)
    - **Deskripsi** (max 1000 karakter)
    - **Kategori Event** (opsional, pilih dari dropdown)
    - **Tanggal & Waktu** (harus waktu yang akan datang)
    - **Lokasi** (max 255 karakter)
    - **Kuota Tiket** (jumlah tiket yang akan dijual)
    - **Banner** (opsional, format: JPG, PNG, GIF, WebP, max 5MB)
4. Klik "Buat Event"

**Form Validasi:**

```php
- name: required, string, max:200
- description: required, string, max:1000
- category_id: nullable, exists:kategori_acara,category_id
- schedule_time: required, date_format:Y-m-d\TH:i, after:now
- location: required, string, max:255
- ticket_quota: required, integer, min:1, max:999999
- banner_url: nullable, image, mimes:jpeg,png,jpg,gif,webp, max:5120
```

### 2. Mengedit Event (Organizer)

**Route:** `PUT /organizer/events/{event}`

**Langkah-langkah:**

1. Masuk ke halaman detail event
2. Klik tombol "Edit"
3. Ubah informasi yang diinginkan
4. Untuk ganti banner: upload file baru (file lama akan dihapus)
5. Untuk pertahankan banner lama: kosongkan input file
6. Ubah status jika diperlukan (Draft, Published, Cancelled)
7. Klik "Simpan Perubahan"

### 3. Melihat Daftar Event

**Route:** `GET /events`

**Fitur:**

- Menampilkan event yang dipublikasi
- Thumbnail banner dengan fallback icon
- Info kategori, jadwal, lokasi, harga mulai
- Pagination 15 event per halaman
- Quick action: Lihat Detail, Edit (jika milik user)

### 4. Melihat Detail Event

**Route:** `GET /events/{event}`

**Menampilkan:**

- Banner event (full width)
- Info lengkap: kategori, status, jadwal, lokasi, kuota, organizer
- Daftar tipe tiket dengan harga dan stok
- Statistik: tiket terjual, sisa tiket, revenue
- Tombol aksi: Edit (untuk organizer), Kelola Tiket, Beli Tiket

### 5. Menghapus Event (Organizer)

**Route:** `DELETE /organizer/events/{event}`

**Langkah-langkah:**

1. Masuk ke halaman edit event
2. Klik tombol "Hapus Event" (warna merah)
3. Konfirmasi penghapusan di modal dialog
4. Event dan semua data terkaitnya akan dihapus

### 6. Publikasikan Event (Organizer)

**Route:** `POST /organizer/events/{event}/publish`

**Persyaratan:**

- Event harus memiliki minimal 1 tipe tiket
- Status akan berubah dari "draft" ke "published"
- Event akan terlihat di halaman publik

### 7. Mengelola Kategori Event (Admin)

**Route:** `GET /admin/categories`

**Fitur:**

- Daftar semua kategori
- Buat kategori baru
- Edit kategori
- Hapus kategori (jika tidak digunakan event)
- Toggle aktif/non-aktif

---

## 💾 File Upload & Storage

### Lokasi Penyimpanan Banner

```
/storage/app/public/events/YYYY/MM/
Contoh: storage/app/public/events/2026/03/event-banner-12345.jpg
```

### URL Publik(Akses dari Browser)

```
/storage/events/2026/03/event-banner-12345.jpg
```

### Hapus Banner

- Otomatis dihapus saat event diupdate dengan banner baru
- Otomatis dihapus saat event dihapus

---

## 🔒 Autorisasi & Permissions

### Event Create/Edit/Delete

- **Organizer**: Dapat manage event milik sendiri
- **Admin**: Dapat manage semua event

### Event View

- **Public**: Event yang published dapat dilihat
- **Authenticated**: Dapat akses halaman event listing

### Category Management

- **Admin Only**: Hanya admin yang dapat CRUD kategori

---

## ✅ Testing Checklist

### Test Create Event

- [ ] Login sebagai organizer
- [ ] Akses halaman create event
- [ ] Isi semua field dengan data valid
- [ ] Upload banner gambar
- [ ] Preview banner menampilkan dengan benar
- [ ] Submit form
- [ ] Event berhasil dibuat (event_id auto-generated)
- [ ] Redirect ke halaman detail event
- [ ] Banner tersimpan di storage/public/events/
- [ ] Dapat dilihat di halaman index

### Test Edit Event

- [ ] Akses halaman edit event
- [ ] Ubah informasi event
- [ ] Upload banner baru
- [ ] Banner lama otomatis dihapus
- [ ] Ubah status ke "published"
- [ ] Simpan perubahan
- [ ] Refresh halaman, data terupdate

### Test Upload Banner

- [ ] Upload JPG - berhasil
- [ ] Upload PNG - berhasil
- [ ] Upload GIF - berhasil
- [ ] Upload WebP - berhasil
- [ ] Upload > 5MB - ditolak dengan error
- [ ] Upload file non-image - ditolak
- [ ] Banner preview tampil di form edit
- [ ] Banner tampil di halaman index/show

### Test Kategori

- [ ] Admin buat kategori baru
- [ ] Pilih kategori saat create event
- [ ] Kategori tampil di detail event
- [ ] Kategori tampil sebagai badge di listing

### Test Jadwal & Lokasi

- [ ] Isi jadwal dengan waktu yang akan datang
- [ ] Isi lokasi dengan alamat lengkap
- [ ] Jadwal tampil dengan format: 01 Jan 2026 - 14:00
- [ ] Lokasi tampil di halaman index/show

### Test Kuota Tiket

- [ ] Input kuota tiket
- [ ] Kuota tampil di halaman event
- [ ] Validasi min 1, max 999999
- [ ] Input 0 atau negatif - ditolak

### Test Publish Event

- [ ] Create event baru (status = draft)
- [ ] Coba publish tanpa tipe tiket - error message
- [ ] Tambah tipe tiket
- [ ] Publish event - berhasil
- [ ] Event tampil di halaman publik
- [ ] Status berubah menjadi "published"

### Test Delete Event

- [ ] Akses halaman edit event
- [ ] Klik "Hapus Event"
- [ ] Modal konfirmasi tampil
- [ ] Klik "Hapus Event" di modal
- [ ] Event terhapus
- [ ] Redirect ke halaman index
- [ ] Banner file dihapus dari storage
- [ ] Tidak ada di database

### Test Validasi Form

- [ ] Kosongkan field required - error
- [ ] Input teks terlalu panjang - error
- [ ] Input format tanggal salah - error
- [ ] Input jadwal di masa lalu - error
- [ ] Upload file terlalu besar - error
- [ ] Semua error message tampil dengan benar

---

## 🐛 Troubleshooting

### Banner tidak tampil

**Solusi:**

1. Pastikan Storage link sudah di-link: `php artisan storage:link`
2. Cek lokasi file: `storage/app/public/events/`
3. Cek permission folder: `chmod -R 755 storage/`

### Event tidak tampil di halaman publik

**Solusi:**

1. Cek status event = "published"
2. Pastikan event memiliki jadwal di masa depan
3. Pastikan event dimiliki oleh organizer yang sudah ter-verifikasi

### Tidak bisa upload banner

**Solusi:**

1. Cek ukuran file < 5MB
2. Cek format file (JPG, PNG, GIF, WebP)
3. Cek permission folder `/storage/app/public/`
4. Cek konfigurasi `config/filesystems.php`

### Event ID tidak terbuat

**Solusi:**

1. Cek Stored Procedure `GenerateEventID` ada
2. Jika SP tidak ada, gunakan fallback: `EV + timestamp + random`
3. Cek error log di `storage/logs/laravel.log`

---

## 📊 Database Query Examples

### Dapatkan semua event organizer

```php
$events = Event::where('organizer_id', auth()->id())
    ->with(['category', 'ticketTypes'])
    ->orderBy('schedule_time', 'desc')
    ->get();
```

### Dapatkan event published dengan kategori tertentu

```php
$events = Event::where('status', 'published')
    ->whereHas('category', fn($q) => $q->where('category_id', $categoryId))
    ->with(['organizer', 'category', 'ticketTypes'])
    ->paginate(15);
```

### Dapatkan event dengan tiket tersisa paling banyak

```php
$event = Event::with('ticketTypes')
    ->where('status', 'published')
    ->selectRaw('events.*,
        SUM(ticket_type.quantity_total - ticket_type.quantity_sold) as available')
    ->orderByDesc('available')
    ->first();
```

---

## 🎯 Next Steps

1. **Tambah Fitur Advanced**
    - Event Cancellation dengan refund otomatis
    - Event Schedule: multiple dates
    - Waiting list untuk sold out event
    - Event Analytics dashboard untuk organizer

2. **Improve UI/UX**
    - Drag-drop banner upload
    - Real-time form validation
    - Event template selection
    - Rich text editor untuk deskripsi

3. **Integration**
    - Payment gateway
    - Email notifications
    - SMS reminders
    - Calendar integration

---

## 📞 Support

Untuk bantuan atau pertanyaan:

1. Cek error log: `storage/logs/laravel.log`
2. Cek browser console: F12 → Console
3. Jalankan migration: `php artisan migrate`
4. Clear cache: `php artisan cache:clear`

---

**Last Updated:** 21 Maret 2026
**Version:** 1.0
**Status:** Siap diproduksi
