# 📊 Admin Dashboard Guide

## Overview

Admin Dashboard adalah pusat kontrol untuk mengelola seluruh platform LuxTix. Dengan dashboard ini, admin dapat mengelola events, menganalisis penjualan, dan membuat laporan ekspor data.

## Fitur-Fitur Utama

### 1. 📈 Dashboard Dashboard Utama (`/admin/dashboard`)

**Statistik Real-time:**

- Total Revenue (Pendapatan Total)
- Total Events
- Total Orders (Pesanan)
- Total Users (Pengguna)
- Revenue Hari Ini
- Revenue Bulan Ini

**Visual Analytics:**

- Grafik Penjualan 7 Hari Terakhir
- Top 5 Events (Event Terlaris)
- Top 5 Organizers (Pengelola Terbaik)

---

### 2. 🎪 Event Management (`/admin/events`)

#### Fitur CRUD Event:

- **List Events:** Lihat semua events dengan filter kategori dan status
- **Create Event:** Buat event baru dengan form lengkap
- **Edit Event:** Update informasi event
- **Delete Event:** Hapus event dari sistem
- **Show Event:** Lihat detail event dan statistik

#### Form Event:

- **Organizer:** Pilih pengelola event
- **Kategori:** Pilih atau buat kategori baru
- **Judul Event:** Nama event
- **Deskripsi:** Deskripsi lengkap event
- **Tanggal & Waktu:** Jadwal event
- **Lokasi:** Tempat event
- **Banner:** Upload banner/poster event (Max: 2MB)
- **Status:** Draft, Published, atau Cancelled

---

### 3. 🏷️ Event Categories (`/admin/categories`)

Kelola kategori event untuk organisasi yang lebih baik.

#### Fitur:

- **Create Category:** Buat kategori baru
- **Edit Category:** Update nama, warna, icon, dan status
- **Delete Category:** Hapus kategori (hanya jika tidak ada event)
- **Color Coding:** Setiap kategori memiliki warna unik

#### Kategori Bawaan:

- Konser Musik 🎵
- Teater 🎭
- Festival 🎪
- Olahraga ⚽
- Seminar 🎓
- Stand Up Comedy 😂
- Pameran 🖼️
- Konferensi 👥

---

### 4 🎫 Ticket Management (`/admin/events/{event}/manage-tickets`)

Atur jenis tiket dan kuota untuk setiap event.

#### Yang Dapat Dilakukan:

- **Tambah Jenis Tiket:** Buat different tier tiket (VIP, Regular, etc)
- **Edit Tiket:** Update harga dan kuota
- **Hapus Tiket:** Hapus jenis tiket (hanya jika belum ada tiket terjual)
- **Monitoring:** Lihat persentase penjualan tiket

#### Detail Tiket:

- Nama Tiket
- Harga
- Kuota Total
- Tiket Terjual
- Tiket Tersedia
- Progress Bar Penjualan

---

### 5. 📊 Analytics (`/admin/analytics`)

#### Sales Analytics (`/admin/analytics/sales`)

**Analisis Penjualan Mendalam:**

- Filter: Harian (7 Hari), Mingguan (30 Hari), Bulanan (90 Hari), Tahunan
- Grafik Revenue & Orders Trend
- Tabel Detail Penjualan per Periode
- Export ke CSV

#### Transaction Analytics (`/admin/analytics/transactions`)

**Analisis Transaksi:**

- Filter: 7, 30, 90, 365 hari terakhir
- Metode Pembayaran
- Status Transaksi (Pending, Paid, Failed)
- Grafik Status Transaksi

#### Event Performance (`/admin/analytics/event-performance`)

**Performa Event:**

- Filter: Semua Kategori atau Kategori Spesifik
- Total Tiket Tersedia & Terjual
- Fill Rate (Tingkat Penjualan)
- Total Revenue per Event
- Jumlah Orders per Event
- Export Performance Report

#### User Statistics (`/admin/analytics/user-stats`)

**Statistik Pengguna:**

- Total Users, Organizers, Admins
- Grafik Pertumbuhan Pengguna (30 Hari)
- Top 10 Pembeli dengan Total Spending
- Revenue dari Top Buyers

#### Revenue by Category

- Breakdown revenue berdasarkan kategori event
- Perbandingan performa kategori

---

### 6. 📥 Export Reports (`/admin/export`)

Unduh laporan dalam format CSV (Excel-compatible).

#### Jenis Export:

1. **Export Events** (`/admin/export/events`)
    - ID, Judul, Organizer, Kategori, Tanggal, Lokasi, Status
    - Tiket Terjual, Total Tiket, Revenue

2. **Export Orders** (`/admin/export/orders`)
    - Order ID, Order Number, User, Event, Amount
    - Status, Created Date

3. **Export Sales Report** (`/admin/export/sales`)
    - Range Date Filter (Start & End Date)
    - Detailed sales per transaction

4. **Export Event Performance** (`/admin/export/event-performance`)
    - Comprehensive performance metrics per event
    - Fill rate, revenue, orders count per event

---

## How To Use

### Accessing Admin Dashboard

1. **Login** sebagai admin
2. Klik menu **Admin Dashboard** atau akses langsung ke `/admin/dashboard`

### Creating New Event

```
1. Go to: Admin Events Management
2. Click "Event Baru" button
3. Fill all required fields (marked with *)
4. Upload banner image (optional but recommended)
5. Click "Simpan Event"
6. After event created, manage tickets for that event
```

### Managing Tickets for Event

```
1. Click event dari list
2. Click "Kelola Tiket" button
3. Fill ticket details:
   - Nama Tiket (e.g., VIP, Regular)
   - Harga per tiket
   - Kuota total tiket
4. Click "Tambah" to add ticket
5. Edit/Delete tickets jika diperlukan
```

### Viewing Analytics

```
1. From Dashboard, click "Analytics & Reports" section
2. Choose specific analytics page
3. Use filters for specific date range or category
4. Click "Export CSV" to download data
```

### Creating Event Category

```
1. Go to: Admin > Categories
2. Click "Kategori Baru"
3. Enter:
   - Nama Kategori
   - Warna (color picker)
   - Icon (optional, Font Awesome)
   - Deskripsi
4. Click "Simpan Kategori"
```

---

## Key Metrics Explained

### Revenue Statistics

- **Total Revenue:** Total pendapatan dari order yang sudah paid
- **Today's Revenue:** Pendapatan hari ini
- **Month Revenue:** Pendapatan bulan ini
- **Per-Event Revenue:** Pendapatan per event

### Order Status

- **Pending:** Order menunggu pembayaran
- **Paid:** Order sudah dibayar
- **Failed:** Pembayaran gagal
- **Expired:** Order kadaluarsa

### Event Status

- **Draft:** Event dalam tahap persiapan
- **Published:** Event sudah live dan visible untuk users
- **Cancelled:** Event dibatalkan

### Ticket Performance

- **Fill Rate:** Persentase tiket yang terjual
- **Available:** Jumlah tiket yang masih tersedia
- **Sold:** Jumlah tiket yang sudah terjual

---

## Tips & Best Practices

1. **Consistent Category Use:** Gunakan kategori yang konsisten untuk better analytics
2. **Banner Upload:** Selalu upload banner berkualitas untuk event (Min: 400x300px)
3. **Ticket Pricing:** Atur pricing dengan strategi yang tepat (VIP lebih mahal dari Regular)
4. **Monitor Sales:** Check dashboard regularly untuk monitor penjualan realtime
5. **Export Reports:** Export laporan bulanan untuk record dan audit
6. **Event Details:** Isi deskripsi event dengan detail untuk menarik more buyers

---

## Database Tables

### event_categories

- id, name, description, icon, color, is_active, timestamps

### events

- id, organizer_id, category_id, title, description, date, location, banner_url, status, timeline_id, timestamps

### ticket_types (already exists as TicketType)

- id, event_id, name, description, price, quantity_total, quantity_sold, timestamps

### orders (already exists)

- id, user_id, event_id, order_number, total_amount, status, timestamps

### payments (already exists)

- id, order_id, amount, payment_method, status, transaction_id, paid_at, timestamps

---

## Routes Overview

```
Admin Dashboard:
- /admin/dashboard                    - Main dashboard

Event Management:
- /admin/events                       - List events
- /admin/events/create               - Create event
- /admin/events/{id}                 - View event
- /admin/events/{id}/edit            - Edit event
- /admin/events/{id}/manage-tickets  - Manage tickets

Categories:
- /admin/categories                  - List categories
- /admin/categories/create           - Create category
- /admin/categories/{id}/edit        - Edit category

Analytics:
- /admin/analytics/sales             - Sales analytics
- /admin/analytics/transactions      - Transaction analytics
- /admin/analytics/event-performance - Event performance
- /admin/analytics/user-stats        - User statistics
- /admin/analytics/revenue-category  - Revenue by category

Exports:
- /admin/export/events               - Export events CSV
- /admin/export/orders               - Export orders CSV
- /admin/export/sales                - Export sales report CSV
- /admin/export/event-performance    - Export performance CSV

Users & Orders:
- /admin/users/admins                - List admins
- /admin/users/organizers            - List organizers
- /admin/users/users                 - List regular users
- /admin/orders                      - List all orders
```

---

## Troubleshooting

### Banner not uploading

- Check file size (max 2MB)
- Verify file format (JPG, PNG, GIF)
- Check `storage/` folder permissions

### Can't edit event with orders

- Events with orders can still be edited (info only)
- Ticket info cannot be modified if already sold

### Empty analytics data

- Wait for sufficient data collection period
- Check date range filters
- Verify there are actual orders in database

---

## Support & Questions

For issues or feature requests, contact the development team.

---

**Last Updated:** March 20, 2026  
**Version:** 1.0
