# Event CRUD System - Referensi Teknis

## 📦 Struktur Komponen

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── EventController.php          ✅ DIUPDATE
│   │       └── Admin/
│   │           └── EventCategoryController.php (sudah ada)
│   │       └── TicketCategoryController.php (manage ticket types)
│   │
│   └── Models/
│       ├── Event.php                        ✅ COMPATIBLE
│       ├── EventCategory.php                ✅ COMPATIBLE
│       ├── TicketType.php                   ✅ COMPATIBLE
│       └── User.php
│
├── resources/views/
│   ├── events/
│   │   ├── create.blade.php                 ✅ DIUPDATE
│   │   ├── edit.blade.php                   ✅ DIUPDATE
│   │   ├── index.blade.php                  ✅ DIUPDATE
│   │   ├── show.blade.php                   ✅ DIUPDATE
│   │
│   └── admin/
│       └── categories/
│           ├── index.blade.php              ✅ EXISTS
│           ├── create.blade.php             ✅ EXISTS
│           └── edit.blade.php               ✅ EXISTS
│
├── routes/
│   └── web.php                              ✅ READY
│
├── database/
│   ├── migrations/
│   │   ├── 2026_03_19_013055_create_events_table.php
│   │   └── 2026_03_20_000001_create_event_categories_table.php
│   │
│   └── seeders/                             (optional)
│
└── storage/
    └── app/public/
        └── events/YYYY/MM/                  (banner uploads)
```

---

## 🔧 EventController - Methods

### Public Methods

#### `index()`

```php
GET /events
- Menampilkan event yang published
- Include: category, organizer, ticketTypes
- Paginate: 15 per halaman
- Return: events.index view
```

#### `show($id)`

```php
GET /events/{event}
- Menampilkan detail event
- Include: organizer, category, ticketTypes, orders
- Return: events.show view
- Check: event exists
```

#### `create()`

```php
GET /events/create
- Show form buat event baru
- Load: categories aktif
- Auth: organizer, admin
- Return: events.create view
```

#### `store(Request $request)`

```php
POST /events
- Simpan event baru
- Validasi: name, description, schedule_time, location, ticket_quota, category_id, banner_url
- Auto-generate: event_id (via SP atau fallback)
- Upload: banner to storage/public/events/YYYY/MM/
- Set: status = 'draft'
- Auth: organizer, admin
- Redirect: events.show
```

#### `edit($id)`

```php
GET /events/{event}/edit
- Show form edit event
- Load: categories
- Auth: owner atau admin
- Check: authorization
- Return: events.edit view
```

#### `update(Request $request, $id)`

```php
PUT /events/{event}
- Update event di database
- Validasi: name, description, schedule_time, location, ticket_quota, status, category_id, banner_url
- Handle: banner upload (replace lama jika ada)
- Delete: old banner jika upload baru
- Auth: owner atau admin
- Redirect: events.show
```

#### `destroy($id)`

```php
DELETE /events/{event}
- Hapus event
- Delete: banner file jika ada
- Cascade: delete ticketTypes
- Auth: owner atau admin
- Redirect: events.index
```

#### `publish($id)`

```php
POST /events/{event}/publish
- Update: status = 'published'
- Check: minimal 1 ticket type ada
- Auth: owner atau admin
- Redirect: back dengan success message
```

#### `cancel($id)`

```php
POST /events/{event}/cancel
- Update: status = 'cancelled'
- Auth: owner atau admin
- Redirect: back dengan success message
```

#### `myEvents()`

```php
GET /my-events
- Daftar event milik current organizer
- Include: category, ticketTypes
- Paginate: 10 per halaman
- Auth: organizer, admin
- Return: events.my-events view
```

---

## 🗂️ Model Relationships

### Event Model

```php
protected $table = 'acara';
protected $primaryKey = 'event_id';
public $keyType = 'string';

// Relations
belongsTo(User::class, 'organizer_id', 'user_id')
belongsTo(EventCategory::class, 'category_id', 'category_id')
hasMany(TicketType::class, 'event_id', 'event_id')
hasMany(Ticket::class, 'event_id', 'event_id')
hasMany(Order::class, 'event_id', 'event_id')

// Accessors
title → name (via getTitleAttribute/setTitleAttribute)

// Casts
schedule_time → datetime
```

### EventCategory Model

```php
protected $table = 'kategori_acara';
protected $primaryKey = 'category_id';
public $timestamps = false;

// Relations
hasMany(Event::class, 'category_id', 'category_id')

// Casts
is_active → boolean
```

### TicketType Model

```php
protected $table = 'ticket_type';
public $timestamps = false;

// Relations
belongsTo(Event::class, 'event_id', 'event_id')

// Methods
availableStock() → quantity_total - quantity_sold
```

---

## 🔑 Database Keys & Constraints

### Primary Keys

- `acara.event_id` (VARCHAR, manual generated)
- `kategori_acara.category_id` (BIGINT UNSIGNED, auto-increment)
- `ticket_type.id` (BIGINT UNSIGNED, auto-increment)

### Foreign Keys

- `acara.organizer_id` → `users.user_id` (CASCADE)
- `acara.category_id` → `kategori_acara.category_id` (SET NULL)
- `ticket_type.event_id` → `acara.event_id` (CASCADE)

### Unique Constraints

- `kategori_acara.name` (UNIQUE)

---

## 📨 Validation Rules

### Event Create/Update

```php
'name' => ['required', 'string', 'max:200']
'description' => ['required', 'string', 'max:1000']
'schedule_time' => ['required', 'date_format:Y-m-d\TH:i', 'after:now'] // create only
'schedule_time' => ['required', 'date_format:Y-m-d\TH:i'] // update only
'location' => ['required', 'string', 'max:255']
'ticket_quota' => ['required', 'integer', 'min:1', 'max:999999']
'category_id' => ['nullable', 'exists:kategori_acara,category_id']
'banner_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120']
'status' => ['required', 'in:draft,published,cancelled'] // update only
```

### EventCategory Create/Update

```php
'name' => ['required', 'string', 'max:100', 'unique:kategori_acara']
'description' => ['nullable', 'string', 'max:500']
'color' => ['required', 'regex:/^#[0-9A-F]{6}$/i']
'icon' => ['nullable', 'string', 'max:50']
'is_active' => ['boolean']
```

---

## 🔐 Authorization Logic

### EventController

```php
// Create/Edit/Delete - hanya owner atau admin
if ($event->organizer_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
    abort(403, 'Anda tidak berhak mengubah event ini.');
}

// View - public untuk published, private untuk draft
```

### EventCategoryController

```php
// Semua methods hanya untuk admin (middleware 'role:admin')
// Delete - check apakah ada event yang menggunakan kategori
if ($category->events()->count() > 0) {
    return back()->with('error', 'Tidak dapat menghapus kategori yang digunakan');
}
```

---

## 📁 View Components

### events/create.blade.php

```blade
- Form untuk membuat event
- Fields: name, description, category_id, schedule_time, location, ticket_quota, banner_url
- Banner preview dengan JavaScript
- Min date validation untuk schedule_time
- Breadcrumb navigation
```

### events/edit.blade.php

```blade
- Form untuk edit event
- Semua fields dari create + status field
- Current banner preview
- Banner upload dengan "retain old" option
- Delete event modal confirmation
- Status dropdown (draft/published/cancelled)
```

### events/index.blade.php

```blade
- Responsive grid layout (3 columns md/lg)
- Event card dengan:
  • Banner thumbnail / fallback icon
  • Category badge
  • Event info: tanggal, lokasi, harga
  • Quick actions: View detail, Edit
- Pagination dengan 15 items per page
- Create button untuk organizer
```

### events/show.blade.php

```blade
- Full-width banner
- Event info grid (tanggal, lokasi, kuota, status, organizer)
- Event description
- Sidebar dengan:
  • List tipe tiket (harga, stok)
  • Progress bar stok
  • Buy ticket button
  • Statistics (sold, available, revenue)
```

---

## 🚀 Routes Mapping

### Public Routes

```
GET  /events                           → index()
GET  /events/{event_id}                → show()
```

### Organizer Routes (auth, role:organizer|admin)

```
GET  /organizer/events/create          → create()
POST /organizer/events                 → store()
GET  /organizer/events/{event}/edit    → edit()
PUT  /organizer/events/{event}         → update()
DELETE /organizer/events/{event}       → destroy()
POST /organizer/events/{event}/publish → publish()
POST /organizer/events/{event}/cancel  → cancel()
```

### Admin Routes (auth, role:admin)

```
GET    /admin/categories               → index()
GET    /admin/categories/create        → create()
POST   /admin/categories               → store()
GET    /admin/categories/{id}/edit     → edit()
PUT    /admin/categories/{id}          → update()
DELETE /admin/categories/{id}          → destroy()
```

---

## 💾 File Storage

### Banner Upload Path

```
/storage/app/public/events/YYYY/MM/filename.jpg

Built dynamically:
$path = $file->store('events/' . date('Y/m'), 'public');

Result:
- Local: storage/app/public/events/2026/03/abc123.jpg
- URL: /storage/events/2026/03/abc123.jpg
```

### File Cleanup

```php
// Manual delete
Storage::disk('public')->delete($event->banner_url);

// Auto delete saat:
1. Update event dengan banner baru
2. Delete event
3. Admin delete event
```

---

## 🠐 Configuration Required

### .env

```
APP_URL=http://localhost:8000
FILESYSTEM_DISK=public
```

### config/filesystems.php

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'path' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
]
```

### Storage Link

```bash
php artisan storage:link
# Creates: public/storage → storage/app/public
```

---

## ⚡ Performance Tips

### Eager Loading

```php
// Good - load relations
$events = Event::with(['category', 'organizer', 'ticketTypes'])
    ->paginate(15);

// Bad - N+1 queries
$events = Event::paginate(15);
foreach ($events as $event) {
    echo $event->category->name; // Query setiap iterasi
}
```

### Pagination

```php
// Load 15 items per halaman (sudah diset di controller)
$events = Event::paginate(15);
```

### Indexing

Suggested indices untuk performance:

```sql
CREATE INDEX idx_event_organizer ON acara(organizer_id);
CREATE INDEX idx_event_category ON acara(category_id);
CREATE INDEX idx_event_status ON acara(status);
CREATE INDEX idx_event_schedule ON acara(schedule_time);
CREATE INDEX idx_ticket_event ON ticket_type(event_id);
```

---

## 🧪 Testing Endpoints

### Create Event

```bash
POST /organizer/events
Content-Type: multipart/form-data

name=Test Event&
description=Testing&
schedule_time=2026-12-25T14:00&
location=Jakarta&
ticket_quota=100&
category_id=1&
banner_url=<file>
```

### Update Event

```bash
PUT /organizer/events/{event_id}
Content-Type: multipart/form-data

name=Updated&
status=published&
banner_url=<optional file>
```

### Delete Event

```bash
DELETE /organizer/events/{event_id}
```

---

## 🔍 Debugging

### Check Event Creation

```php
php artisan tinker
> $events = App\Models\Event::latest()->take(5)->get();
> $event = $events[0];
> echo $event->name; // Check data
> echo $event->banner_url; // Check banner path
> echo \Storage::disk('public')->exists($event->banner_url); // Check file
```

### Check Authorization

```php
> $user = App\Models\User::find(1);
> Auth::login($user);
> $event = App\Models\Event::first();
> echo $user->user_id . ' == ' . $event->organizer_id; // Should match
```

### Check Routes

```bash
php artisan route:list | grep event
```

### Check Storage Link

```bash
ls -la public/storage
# Should be symlink to storage/app/public
```

---

**Dokumentasi Lengkap:** [EVENT_CRUD_GUIDE.md](EVENT_CRUD_GUIDE.md)
**Testing Guide:** [TEST_EVENT_CRUD.php](TEST_EVENT_CRUD.php)
