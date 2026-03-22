# 🚀 Event CRUD - Quick Reference Card

## URLs Reference

```
PUBLIC ROUTES
GET  /events                        → Daftar event published
GET  /events/{event_id}            → Detail event

ORGANIZER ROUTES (Login Required)
GET  /events/create                → Form buat event
POST /events                       → Simpan event
GET  /events/{event_id}/edit       → Form edit event
PUT  /events/{event_id}            → Update event
DELETE /events/{event_id}          → Hapus event
POST /events/{event_id}/publish    → Publish event

ADMIN ROUTES
GET  /admin/categories             → Daftar kategori
GET  /admin/categories/create      → Buat kategori
...  (lihat routes/web.php)
```

---

## Database Fields

### acara table

```
field                  | type          | required | notes
event_id              | VARCHAR(50)   | ✓        | Primary key, auto-generated
organizer_id          | BIGINT UNSIGNED| ✓       | Foreign key to users
category_id           | BIGINT UNSIGNED| ✗       | Foreign key to kategori_acara
name                  | VARCHAR(200)  | ✓        | Event title
description           | VARCHAR(1000) | ✓        | Event description
location              | VARCHAR(255)  | ✓        | Event location
schedule_time         | DATETIME      | ✓        | Event date & time
ticket_quota          | INT           | ✓        | Total quota
banner_url            | VARCHAR       | ✗        | Banner path (nullable)
status                | ENUM          | ✓        | draft/published/cancelled
```

### kategori_acara table

```
field        | type           | required | notes
category_id  | BIGINT UNSIGNED| ✓        | Primary key, auto-increment
name         | VARCHAR(100)   | ✓        | Category name
description  | TEXT           | ✗        | Description
icon         | VARCHAR(50)    | ✗        | Icon class name
color        | VARCHAR(7)     | ✓        | Hex color (#XXXXXX)
is_active    | BOOLEAN        | ✓        | Default: 1
created_at   | TIMESTAMP      | ✓        | Auto-filled
updated_at   | TIMESTAMP      | ✓        | Auto-filled
```

---

## Form Input Validation

### Event Create/Edit

```
Field           | Type    | Min | Max  | Required | Notes
name            | text    | 1   | 200  | YES      | Event title
description     | textarea| 1   | 1000 | YES      | Event description
category_id     | select  | -   | -    | NO       | Dropdown
schedule_time   | datetime| -   | -    | YES      | Must be future
location        | text    | 1   | 255  | YES      | Event location
ticket_quota    | number  | 1   | 999k | YES      | Total tickets
banner_url      | file    | -   | 5MB  | NO       | JPG/PNG/GIF/WebP
status          | select  | -   | -    | YES      | draft/pub/cancel
```

---

## Common Errors & Solutions

### ❌ Banner shows broken image

```
✓ Solution: php artisan storage:link
✓ Check: storage/app/public/events/ exists
✓ Check: public/storage symlink exists
```

### ❌ "You are not authorized"

```
✓ Check: You own the event
✓ Check: Admin role (if not owner)
✓ Organizer can only edit own events
```

### ❌ Event ID not generated

```
✓ Check: Stored Procedure GenerateEventID exists
✓ Fallback: Uses EV + timestamp + random
✓ Check: error log at storage/logs/laravel.log
```

### ❌ "Can't delete - category in use"

```
✓ Remove category from events first
✓ Change events to different category
✓ Then delete category
```

### ❌ Form validation errors

```
✓ Check field requirements
✓ Check field max/min limits
✓ Schedule must be in future
✓ Banner < 5MB & correct format
```

---

## Testing Quick Commands

```bash
# View events in database
php artisan tinker
>>> App\Models\Event::orderBy('created_at','desc')->take(5)->get()

# Check specific event
>>> $e = App\Models\Event::first()
>>> echo $e->name; echo $e->banner_url;

# Check if banner file exists
>>> \Storage::disk('public')->exists($e->banner_url)

# List all organizers
>>> App\Models\User::where('role_id', 2)->get()

# Count events by status
>>> App\Models\Event::where('status','published')->count()

# Clear cache
>>> cache()->flush()
```

---

## File Locations Cheat Sheet

```
📁 Controllers
   app/Http/Controllers/EventController.php

📁 Views
   resources/views/events/create.blade.php
   resources/views/events/edit.blade.php
   resources/views/events/index.blade.php
   resources/views/events/show.blade.php

📁 Models
   app/Models/Event.php
   app/Models/EventCategory.php

📁 Routes
   routes/web.php

📁 Storage
   storage/app/public/events/YYYY/MM/

📁 Logs
   storage/logs/laravel.log

📁 Database
   config/database.php
   database/migrations/
```

---

## Authorization Rules (Simplified)

```
ACTION          | ORGANIZER | ADMIN | PUBLIC
Create event    | ✓         | ✓     | ✗
View published  | ✓         | ✓     | ✓
View own draft  | ✓         | ✗     | ✗
Edit own        | ✓         | ✗     | ✗
Edit any        | ✗         | ✓     | ✗
Delete own      | ✓         | ✗     | ✗
Delete any      | ✗         | ✓     | ✗
Manage category | ✗         | ✓     | ✗
Publish event   | ✓*        | ✓     | ✗
               (* owner)
```

---

## Status Meanings

| Status    | Location       | Visible | Can Edit | Can Delete |
| --------- | -------------- | ------- | -------- | ---------- |
| draft     | Organizer only | No      | Yes      | Yes        |
| published | Public         | Yes     | Yes      | Yes\*      |
| cancelled | Hidden         | No      | Yes      | Yes        |

\*Can delete published but data is permanent

---

## Form Flow Diagram

```
START
  ↓
[Login] ← Required for organizers
  ↓
[Create Event] ← Click "Buat Event Baru"
  ↓
[Fill Form] ← All fields + upload banner
  ↓
[Validate] ← Server side checks
  ↓
  YES ✓          NO ✗
  ↓             ↓
[Save] → [Show Error] ← Back to form
  ↓
[Redirect → Detail] ← Success message
  ↓
[Add Tickets] ← Required before publish
  ↓
[Publish] ← Change status to published
  ↓
[Visible on /events] ← Public can see
```

---

## Database Diagram (Simplified)

```
┌─────────────────┐
│     users       │
├─────────────────┤
│ user_id (PK)    │
│ nama_lengkap    │
│ role_id         │
└────────┬────────┘
         │
         │ organizer_id
         ↓
┌─────────────────────┐          ┌──────────────────┐
│       acara         │◄─────────│ kategori_acara   │
├─────────────────────┤ category ├──────────────────┤
│ event_id (PK)       │          │ category_id (PK) │
│ organizer_id (FK)   │          │ name             │
│ category_id (FK)    │          │ is_active        │
│ name                │          └──────────────────┘
│ description         │
│ location            │
│ schedule_time       │
│ ticket_quota        │
│ banner_url          │
│ status              │
└────────┬────────────┘
         │
         │ event_id
         ↓
┌─────────────────────┐
│    ticket_type      │
├─────────────────────┤
│ id (PK)             │
│ event_id (FK)       │
│ name                │
│ price               │
│ quantity_total      │
│ quantity_sold       │
└─────────────────────┘
```

---

## Keyboard Shortcuts (Form)

```
Ctrl+S          → Submit form (if enabled)
Tab             → Next field
Shift+Tab       → Previous field
Esc             → Close modal
Enter (on date) → Open date picker
Click file      → Open file dialog
```

---

## Response Formats

### Success Create

```
Status: 201 Created
Redirect: /events/{event_id}
Message: "Event berhasil dibuat! Silakan tambahkan tipe tiket."
```

### Success Update

```
Status: 200 OK
Redirect: /events/{event_id}
Message: "Event berhasil diperbarui."
```

### Success Delete

```
Status: 200 OK
Redirect: /events
Message: "Event berhasil dihapus."
```

### Validation Error

```
Status: 422 Unprocessable Entity
Fields: Array of errors
Messages: Validation rule violated
```

### Authorization Error

```
Status: 403 Forbidden
Message: "Anda tidak berhak mengubah event ini."
```

---

## Browser DevTools Tips

```
Press F12 to open console

Check errors:
→ Console tab (red messages)
→ Network tab (failed requests)
→ Storage tab (cookies, session)

Inspect elements:
→ Right click element → Inspect
→ Modify form values for testing
→ Check data-* attributes
```

---

## Environment Variables

```bash
# .env file
APP_URL=http://localhost:8000      # Change domain here
FILESYSTEM_DISK=public             # Storage disk
DB_CONNECTION=mysql                # Database driver
DB_DATABASE=tixly                  # Database name
DB_HOST=127.0.0.1                  # Database host
APP_DEBUG=true                     # Debugging (false in production)
```

---

## Useful Artisan Commands

```bash
# Development
php artisan serve                  # Start dev server
php artisan tinker                 # Interactive shell

# Database
php artisan migrate                # Run migrations
php artisan migrate:fresh          # Reset & migrate
php artisan db:seed                # Seed data

# Caching
php artisan cache:clear            # Clear cache
php artisan config:clear           # Clear config cache
php artisan cache:forget           # Delete specific cache

# Storage
php artisan storage:link           # Link public storage

# Logs
php artisan log:tail               # Real-time logs
```

---

## Success Indicators

✅ **System Working If:**

- Banner uploads & displays
- Form validations work
- Event saves to database
- Status changes publish correctly
- Category selects & displays
- File storage has files
- Authorization restricts properly
- Tables show correct data

---

**Quick Reference Card** | _Print this for quick access_
Last Updated: 21 Maret 2026
