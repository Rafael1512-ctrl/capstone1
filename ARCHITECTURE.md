# 🏗️ LuxTix Architecture Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        USER INTERFACE LAYER                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Landing → Register → Login → Dashboard (Role-specific)          │
│                                  ├── Admin Dashboard             │
│                                  ├── Organizer Dashboard         │
│                                  └── User Dashboard              │
│                                                                   │
│  Secondary Routes:                                               │
│  ├── Events Browsing                                             │
│  ├── Order Management                                            │
│  ├── Ticket Management                                           │
│  ├── Payment Processing                                          │
│  └── Ticket Validation                                           │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                      ROUTING LAYER (routes/web.php)              │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Public Routes → Guest Middleware                                │
│  Auth Routes → Auth Middleware                                   │
│  Organizer Routes → Auth + Organizer Middleware                  │
│  Admin Routes → Auth + Admin Middleware                          │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER (HTTP Middleware)             │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ├── Guest Middleware (for register/login routes)               │
│  ├── Auth Middleware (general authentication)                    │
│  ├── EnsureUserIsAdmin (admin-only access)                       │
│  ├── EnsureUserIsOrganizer (organizer+admin access)             │
│  └── CheckRole (flexible role checking)                         │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                  CONTROLLER LAYER (Business Logic)               │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────────┐    ┌──────────────────┐                     │
│  │ AuthController  │    │ DashboardController                    │
│  │ ├─ showRegister │    │ ├─ adminDashboard                     │
│  │ ├─ register     │    │ ├─ organizerDashboard                 │
│  │ ├─ showLogin    │    │ └─ userDashboard                      │
│  │ ├─ login        │    │                                        │
│  │ └─ logout       │    └──────────────────┘                     │
│  └─────────────────┘                                             │
│                                                                   │
│  ┌────────────────────┐  ┌──────────────────────┐                │
│  │ EventController    │  │ OrderController      │                │
│  │ ├─ index          │  │ ├─ create            │                │
│  │ ├─ show           │  │ ├─ store             │                │
│  │ ├─ create         │  │ ├─ show              │                │
│  │ ├─ store          │  │ ├─ index             │                │
│  │ ├─ edit           │  │ └─ cancel            │                │
│  │ ├─ update         │  │ → Generates Tickets  │                │
│  │ ├─ destroy        │  │ → Manages Inventory  │                │
│  │ └─ publish        │  └──────────────────────┘                │
│  └────────────────────┘                                          │
│                                                                   │
│  ┌──────────────────────┐  ┌─────────────────────┐               │
│  │ PaymentController    │  │ TicketController    │               │
│  │ ├─ show             │  │ ├─ generateQrCode    │               │
│  │ ├─ process          │  │ ├─ download          │               │
│  │ ├─ verify           │  │ ├─ view              │               │
│  │ └─ refund           │  │ ├─ validate/scan     │               │
│  │ → Dummy Gateway     │  │ └─ myTickets         │               │
│  │ → Transaction Log   │  │ → QR Code           │                │
│  └──────────────────────┘  └─────────────────────┘               │
│                                                                   │
│  ┌─────────────────────────────────────────────────────┐         │
│  │ TicketCategoryController │ AdminDashboardController │         │
│  │ ├─ index                 │ ├─ stats               │         │
│  │ ├─ create                │ ├─ analytics           │         │
│  │ ├─ store                 │ ├─ topEvents           │         │
│  │ ├─ edit                  │ └─ revenueByCategory   │         │
│  │ ├─ update                │                         │         │
│  │ └─ destroy               │                         │         │
│  └─────────────────────────────────────────────────────┘         │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    POLICY LAYER (Authorization)                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────┐    ┌────────────────┐                      │
│  │ EventPolicy      │    │ OrderPolicy    │                      │
│  │ ├─ view          │    │ ├─ view        │                      │
│  │ ├─ create        │    │ └─ update      │                      │
│  │ ├─ update        │    │                │                      │
│  │ └─ delete        │    └────────────────┘                      │
│  │                  │                                             │
│  │ Rules:           │    Rules:                                  │
│  │ - Owner or Admin │    - Owner or Admin                       │
│  │ - Only published │    - Only own orders                      │
│  │   visible to all │                                            │
│  └──────────────────┘                                            │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│              MODEL LAYER (Data & Relationships)                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────────────────────────────────────────────┐         │
│  │                       USER                          │         │
│  │  id, name, email, password, role                    │         │
│  │  phone, bio, avatar, last_login                     │         │
│  │  • isAdmin() • isOrganizer() • isUser()            │         │
│  └─────────────────────────────────────────────────────┘         │
│         ↓                    ↓              ↓                      │
│      hasMany           hasMany         hasMany                   │
│       Events            Orders       Transactions                │
│                                                                   │
│  ┌────────────────────────┐   ┌────────────────────────┐         │
│  │        EVENT           │   │       ORDER            │         │
│  │ id, organizer_id       │   │ id, user_id, event_id  │         │
│  │ title, description     │   │ order_number           │         │
│  │ location, venue_name   │   │ quantity, total_price  │         │
│  │ event_date, start_time │   │ status, payment_method │         │
│  │ total_capacity, status │   │                        │         │
│  │ base_price, image      │   │ isPaid() / isPending() │         │
│  └────────────────────────┘   └────────────────────────┘         │
│         ↓                              ↓                         │
│      hasMany                      hasMany                        │
│   TicketCategories                Tickets                        │
│                                        ↓                         │
│                                  Ticket                          │
│  ┌────────────────────────────┐────────────┐                    │
│  │   TICKET CATEGORY          │   TICKET   │                    │
│  │ id, event_id               │ id, order_ │                    │
│  │ name, price                │ event_id   │                    │
│  │ total_tickets              │ category_  │                    │
│  │ available_tickets          │ ticket_    │                    │
│  │ sold_tickets, queue_count  │ qr_code_   │                    │
│  │ status                     │ status     │                    │
│  │                            │ used_at    │                    │
│  │ • decreaseAvailable()      │ validated_ │                    │
│  │ • increaseAvailable()      │ validate() │                    │
│  └────────────────────────────┴────────────┘                    │
│                 ↓                                                │
│            hasMany                                              │
│         WaitingLists                                            │
│                                                                  │
│  ┌────────────────────────────────────────────┐                │
│  │        TRANSACTION                          │                │
│  │ id, order_id, user_id                       │                │
│  │ transaction_code, amount                    │                │
│  │ status (pending/completed/failed/refunded) │                │
│  │ payment_method, transaction_id              │                │
│  │ completed_at, created_at                    │                │
│  └────────────────────────────────────────────┘                │
│                                                                  │
│  ┌────────────────────────────────────────────┐                │
│  │        WAITING LIST                         │                │
│  │ id, user_id, event_id, category_id          │                │
│  │ quantity, position                          │                │
│  │ status (waiting/notified/accepted/expired) │                │
│  │ notified_at, accepted_at, expires_at       │                │
│  └────────────────────────────────────────────┘                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   DATABASE LAYER (MySQL/SQLite)                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Tables:                                                         │
│  • users (with role field)                                      │
│  • events                                                        │
│  • ticket_categories                                             │
│  • orders                                                        │
│  • tickets                                                       │
│  • transactions                                                  │
│  • waiting_lists                                                 │
│                                                                   │
│  Relationships: Foreign keys + Indexes for performance           │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Data Flow Diagrams

### User Registration Flow

```
User Input
    ↓
AuthController::register()
    ↓
Validate Input
    ↓
Hash Password (bcrypt)
    ↓
Create User Record
    ↓
Store Role (user/organizer)
    ↓
Trigger Registered Event
    ↓
Auto-login User
    ↓
Redirect to Dashboard
```

### Event Creation Flow (Organizer)

```
Organizer Input
    ↓
EventController::store()
    ↓
Validate Event Data
    ↓
Upload Image (optional)
    ↓
Create Event (status: draft)
    ↓
Redirect to Event Page
    ↓
Organizer adds TicketCategories
    ↓
EventController::publish()
    ↓
Event Available to New Users
```

### Ticket Purchase Flow

```
User Browse Events
    ↓
View Event Details
    ↓
Click "Buy Tickets"
    ↓
OrderController::create()
    ↓
Select Ticket Type & Quantity
    ↓
Check Availability (TicketCategory::available)
    ↓
Create Order (status: pending)
    ↓
Decrease Stock (TicketCategory::decreaseAvailable)
    ↓
Generate Tickets (create Ticket records)
    ↓
Redirect to Payment
    ↓
User Enters Payment Details
    ↓
PaymentController::process()
    ↓
Simulate Payment (95% success)
    ↓
Create Transaction Record
    ↓
Mark as Completed/Failed
    ↓
Update Order Status
    ↓
Generate QR Codes (on demand)
    ↓
Send Confirmation Email
    ↓
E-Tickets Ready for Download
```

### Ticket Validation Flow

```
Event Staff Has Scanner
    ↓
User Shows QR Code
    ↓
Scan QR Code
    ↓
Get Ticket Number (TKT-YYYYMMDD-XXXXX)
    ↓
TicketController::validate()
    ↓
Find Ticket by Number
    ↓
Check Status (must be active)
    ↓
Verify User Identity
    ↓
Mark as Used
    ↓
Record Timestamp + Staff Name
    ↓
Save to Database
    ↓
Show Confirmation
    ↓
Grant Event Access
```

### Waiting List Flow

```
User Tries to Buy Sold-Out Tickets
    ↓
OrderController::store()
    ↓
Check Availability
    ↓
No Available Tickets
    ↓
Offer Waiting List Option
    ↓
User Joins Waiting List
    ↓
Create WaitingList Record
    ↓
Assign Position (FIFO order)
    ↓
Increment queue_count on Category
    ↓
Show Position to User
    ↓
Wait for Notification
    ↓
[When Ticket Available]
    ↓
Mark as "notified"
    ↓
Send Email Notification
    ↓
Start 24-hour Timer
    ↓
User Accepts → Convert to Order
    ↓
OR Expires → Mark as expired
```

---

## Technology Stack

```
┌────────────────────────────────────────────────────────────┐
│                     Frontend Layer                          │
├────────────────────────────────────────────────────────────┤
│  Framework: Bootstrap 5 (CSS Framework)                     │
│  Templating: Blade (Laravel's template engine)             │
│  Files: resources/views/*.blade.php                         │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│                   Application Layer                         │
├────────────────────────────────────────────────────────────┤
│  Framework: Laravel 12                                      │
│  Language: PHP 8.2+                                        │
│  Pattern: MVC (Model-View-Controller)                      │
│  Package Manager: Composer                                  │
│                                                              │
│  Core Components:                                           │
│  • Controllers (app/Http/Controllers)                       │
│  • Models (app/Models)                                      │
│  • Migrations (database/migrations)                         │
│  • Routes (routes/web.php)                                  │
│  • Middleware (app/Http/Middleware)                         │
│  • Policies (app/Policies)                                  │
│  • Providers (app/Providers)                                │
│                                                              │
│  Key Packages:                                              │
│  • laravel/framework                                        │
│  • simplesoftware/simple-qrcode (for QR codes)             │
│  • barryvdh/laravel-dompdf (for PDF export)                │
│  • maatwebsite/excel (for Excel export)                    │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│                   Database Layer                            │
├────────────────────────────────────────────────────────────┤
│  Database: MySQL 5.7+ or SQLite 3                           │
│  ORM: Eloquent (Laravel's ORM)                              │
│  Migrations: database/migrations/                           │
│  Connection: .env configuration                             │
│                                                              │
│  Tables (7 total):                                          │
│  • users (with roles)                                       │
│  • events                                                    │
│  • ticket_categories                                         │
│  • orders                                                    │
│  • tickets                                                   │
│  • transactions                                              │
│  • waiting_lists                                            │
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│                   External Services                         │
├────────────────────────────────────────────────────────────┤
│  Payment Gateway: Dummy (simulated)                         │
│  Email Service: Log driver (for testing)                    │
│  File Storage: Local + Public (storage/app/public)          │
│  QR Code Generator: SimpleSoftwareIO/QrCode                │
└────────────────────────────────────────────────────────────┘
```

---

## File Structure

```
laravel-project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── EventController.php
│   │   │   ├── TicketCategoryController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── TicketController.php
│   │   │   └── AdminDashboardController.php
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   ├── EnsureUserIsAdmin.php
│   │   │   └── EnsureUserIsOrganizer.php
│   │   └── Requests/ (Form Requests)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Event.php
│   │   ├── TicketCategory.php
│   │   ├── Order.php
│   │   ├── Ticket.php
│   │   ├── Transaction.php
│   │   └── WaitingList.php
│   ├── Policies/
│   │   ├── EventPolicy.php
│   │   └── OrderPolicy.php
│   ├── Providers/
│   │   └── AuthServiceProvider.php
│   └── Notifications/ (optional)
│
├── bootstrap/
│   ├── app.php (Middleware registration)
│   └── providers.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── mail.php
│
├── database/
│   ├── migrations/ (7 migration files)
│   ├── factories/ (Factories for testing)
│   └── seeders/ (Database seeders)
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── dashboard/
│       │   ├── admin.blade.php
│       │   ├── organizer.blade.php
│       │   └── user.blade.php
│       ├── events/
│       │   ├── index.blade.php
│       │   ├── show.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── orders/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── show.blade.php
│       ├── payments/
│       │   └── show.blade.php
│       ├── tickets/
│       │   ├── view.blade.php
│       │   └── my-tickets.blade.php
│       ├── ticket-categories/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── admin/
│       │   ├── users/
│       │   │   └── index.blade.php
│       │   ├── analytics.blade.php
│       │   └── reports.blade.php
│       └── layouts/
│           ├── master.blade.php
│           ├── sidebar.blade.php
│           ├── header.blade.php
│           └── footer.blade.php
│
├── routes/
│   └── web.php (All routes)
│
├── storage/
│   └── app/
│       └── public/
│           ├── qr-codes/ (QR code storage)
│           ├── events/ (Event images)
│           └── avatars/ (User avatars)
│
├── public/
│   ├── index.php (Entry point)
│   ├── assets/ (CSS, JS, images)
│   ├── concert-assets/ (Existing templates)
│   └── cardboard-assets/ (Existing templates)
│
├── composer.json (PHP dependencies)
├── package.json (Node dependencies)
├── .env (Environment configuration)
├── .env.example (Example environment)
│
├── SETUP_GUIDE.md (Complete documentation)
├── QUICKSTART.md (Quick start guide)
└── IMPLEMENTATION_SUMMARY.md (This file)
```

---

## Request/Response Flow

```
HTTP Request
    ↓
Entry: public/index.php
    ↓
Bootstrap: bootstrap/app.php
    ↓
Route Matching: routes/web.php
    ↓
Global Middleware (CSRF, Auth, etc.)
    ↓
Route Middleware (Guest, Auth, Admin, Organizer)
    ↓
Controller Method
    ↓
Model Interaction (Query/Update Data)
    ↓
Policy Check (if applicable)
    ↓
Business Logic
    ↓
View Rendering (Blade Template) OR JSON Response
    ↓
HTTP Response
    ↓
Browser/Client
```

---

## Security Layers

```
┌────────────────────────────────────────────┐
│         APPLICATION SECURITY               │
├────────────────────────────────────────────┤
│                                            │
│  1. Authentication Layer                   │
│     └─ Login validation                    │
│     └─ Password hashing (bcrypt)           │
│     └─ Session management                  │
│                                            │
│  2. Authorization Layer                    │
│     └─ Role-based access (Admin, Org, User)
│     └─ Policies (EventPolicy, OrderPolicy) │
│     └─ Middleware guards                   │
│                                            │
│  3. Input Validation                       │
│     └─ Form validation (Rules)             │
│     └─ CSRF token verification             │
│                                            │
│  4. Query Protection                       │
│     └─ Eloquent ORM (prevents SQL inject)  │
│     └─ Parameterized queries               │
│                                            │
│  5. Data Protection                        │
│     └─ Password hidden in serialization    │
│     └─ Email verification ready            │
│                                            │
└────────────────────────────────────────────┘
```

---

## Configuration Files

### Key Environment Variables (.env):

```
APP_NAME=LuxTix
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=luxtix
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@luxtix.com

FILESYSTEM_DISK=public
```

### Key Configuration Files:

- `config/app.php` - Application settings
- `config/auth.php` - Authentication defaults
- `config/database.php` - Database connections
- `config/mail.php` - Mail configuration
- `config/session.php` - Session configuration

---

## Performance Considerations

```
Query Optimization:
├── Eager Loading (with())
├── Database Indexing
└── Pagination (15 items/page)

Caching:
├── Route caching
├── Config caching
├── View caching
└── Query result caching

File Storage:
├── Local storage for QR codes
├── Public storage for images
└── Symlink to public/storage

Database Indexes (auto-created on foreign keys):
├── user_id
├── event_id
├── order_id
├── status fields
└── created_at
```

---

## Scalability Path

```
Current: Single Server Development
    ↓
Phase 1: Caching Layer
    └─ Redis for sessions
    └─ Cache frequently accessed data

Phase 2: Queue System
    └─ Email sending (async)
    └─ PDF generation (background)

Phase 3: Load Balancing
    └─ Multiple application servers
    └─ Database replication

Phase 4: Microservices
    └─ Payment service (separate)
    └─ Notification service (separate)
    └─ Analytics service (separate)
```

---

This architecture is designed to be:

- ✅ **Modular** - Easy to extend with new features
- ✅ **Secure** - Multiple security layers
- ✅ **Scalable** - Can support growth
- ✅ **Maintainable** - Clean code structure
- ✅ **Testable** - Clear separation of concerns

Ready for both development and production deployment!
