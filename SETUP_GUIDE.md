# Tixly Event Management & Ticketing Platform

## Project Overview

LuxTix is a comprehensive web-based platform for managing events and selling tickets with:

- **Multi-role authentication** (Admin, Organizer, User)
- **Digital ticketing system** with QR code generation
- **Real-time inventory management**
- **Payment simulation** system
- **Waiting list** for sold-out events
- **Comprehensive analytics** and reporting
- **Email notifications** system

## Technology Stack

- **Framework:** Laravel 12
- **Database:** MySQL/SQLite
- **Frontend:** Bootstrap 5
- **Authentication:** Laravel's built-in auth system
- **QR Code:** SimpleSoftwareIO/QrCode (requires installation)
- **Payment:** Simulated gateway (dummy payment processor)

## Installation & Setup

### 1. Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/SQLite (configured in .env)

### 2. Initial Setup

```bash
# Clone/Open the project
cd d:\capstone1

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
copy .env.example .env

# Generate app key
php artisan key:generate

# Create database and run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 3. Install Required Packages

```bash
# Install QR Code package (for ticket generation)
composer require simplesoftware/simple-qrcode

# For PDF export (if needed)
composer require barryvdh/laravel-dompdf

# For Excel export (if needed)
composer require maatwebsite/excel
```

### 4. Configure Storage

```bash
# Link storage for public file access
php artisan storage:link
```

### 5. Start Development Server

```bash
# Option 1: Using Laravel's built-in server
php artisan serve

# Option 2: Using npm dev server (recommended)
npm run dev

# In another terminal: Start the payment queue (for async processing)
php artisan queue:listen
```

Access the application at: `http://localhost:8000`

---

## Database Schema

### Users Table (Extended)

- `id`, `name`, `email`, `password`
- `role` (user, organizer, admin) - **NEW**
- `phone`, `bio`, `avatar`, `last_login` - **NEW**

### Events Table (NEW)

- `id`, `organizer_id` (foreign key to users)
- `title`, `description`, `brief_description`
- `image`, `location`, `venue_name`
- `event_date`, `start_time`, `end_time`
- `total_capacity`, `status` (draft, published, ongoing, completed, cancelled)
- `base_price`

### Ticket Categories Table (NEW)

- `id`, `event_id` (foreign key)
- `name` (VIP, Regular, Student, etc.)
- `description`, `price`
- `total_tickets`, `available_tickets`, `sold_tickets`, `queue_count`
- `status` (active, inactive, sold_out)

### Orders Table (NEW)

- `id`, `user_id` (foreign key), `event_id` (foreign key)
- `order_number` (unique, auto-generated)
- `quantity`, `total_price`
- `status` (pending, paid, failed, cancelled, completed)
- `payment_method` (dummy, credit_card, bank_transfer, wallet)

### Tickets Table (NEW)

- `id`, `order_id`, `event_id`, `ticket_category_id`
- `ticket_number` (unique, auto-generated)
- `qr_code`, `qr_code_path` - **QR Code Data**
- `status` (active, used, cancelled, voided)
- `used_at`, `validated_at`, `validated_by`

### Transactions Table (NEW)

- `id`, `order_id`, `user_id`
- `transaction_code` (unique)
- `amount`, `status` (pending, completed, failed, refunded)
- `payment_method`, `payment_gateway_response`
- `transaction_id` (external payment ID)
- `processed_at`, `completed_at`

### Waiting Lists Table (NEW)

- `id`, `user_id`, `event_id`, `ticket_category_id`
- `quantity`, `position` (queue position)
- `status` (waiting, notified, accepted, expired, cancelled)
- `notified_at`, `accepted_at`, `expires_at`

---

## User Roles & Permissions

### 1. Admin Role

- ✅ View all events, orders, and transactions
- ✅ Manage all users
- ✅ View system-wide analytics and reports
- ✅ Can impersonate organizers (create events on their behalf)
- ✅ Generate and export reports
- **Routes:** `/admin/*`

### 2. Organizer Role

- ✅ Create and manage own events
- ✅ Add ticket categories to events
- ✅ View orders for own events
- ✅ Generate reports for own events
- ✅ Validate/scan tickets at event
- ✅️ Access organizer dashboard
- **Routes:** `/organizer/*`

### 3. User (Buyer) Role

- ✅ Browse all published events
- ✅ Purchase tickets
- ✅ View their orders and tickets
- ✅ Download e-tickets with QR codes
- ✅ Join waiting lists
- ✅ Track order status
- ✅ View their tickets
- **Routes:** User dashboard, events browse, orders, tickets

---

## Key Features Implementation

### 1. Authentication & Authorization

**Login Flow:**

```
User → Login Page → Auth::attempt() → Check Role →
Dashboard (role-specific)
```

**Protected Routes:**

- All authenticated routes use `auth` middleware
- Role-specific routes use `admin`, `organizer` middleware
- Event/Order access controlled via Policies

**Middleware Stack:**

- `EnsureUserIsAdmin` - Admin-only access
- `EnsureUserIsOrganizer` - Organizer/Admin access
- `CheckRole` - Flexible role checking

### 2. Ticketing System

**Ticket Generation Process:**

```
1. User selects event → Browse ticket categories
2. Chooses quantity → Create Order (pending status)
3. System auto-generates Tickets tied to Order
4. QR codes generated when first accessed
5. Payment → Order marked as "paid"
6. E-tickets ready for download
```

**Key Features:**

- ✅ Real-time stock management
- ✅ Atomic transactions (prevents overselling)
- ✅ Waiting list when sold out
- ✅ Unique ticket numbers (TKT-YYYYMMDD-XXXXX)
- ✅ QR code generation on demand
- ✅ Ticket validation/scanning

### 3. Payment Simulation

**Payment Gateway (Dummy):**

- Simulates payment processing
- 95% success rate (test mode)
- Card numbers ending in "00" will fail
- Callback mechanism for webhook simulation
- Transaction logging and tracking

**Payment Flow:**

```
Order Created (pending) →
Payment Page → Enter Details →
Process Payment →
Update Transaction Status →
Mark Order as Paid →
Send Confirmation Email
```

### 4. QR Code & Ticket Validation

**QR Code Generation:**

```php
// In TicketController::generateQrCode()
QrCode::format('png')->size(300)->generate($ticket->ticket_number);
// Stored in storage/app/public/qr-codes/
```

**Validation (Scan):**

```php
// POST /tickets/scan or /tickets/validate
// Admin/Organizer scans QR code → System marks ticket as "used"
// Returns ticket details + event info
```

### 5. Waiting List Management

**Automatic Waiting List:**

- When tickets sold out: User → Join waiting list
- Queue position auto-assigned
- Admin notifies when tickets available
- 24-hour window to accept offer
- Auto-expires if not accepted

### 6. Analytics & Dashboard

**Admin Dashboard:**

- Total users, organizers, events, orders
- Revenue tracking
- Recent transactions
- Top-performing events
- System health metrics

**Organizer Dashboard:**

- Events created
- Total orders & revenue
- Pending orders
- Recent order activity
- Quick event management

**User Dashboard:**

- My tickets (with QR codes)
- Order history
- Upcoming events
- Ticket status tracking

---

## API Endpoints

### Public Routes

```
GET  /                           # Landing page
GET  /events                     # Browse events
GET  /events/{event}             # Event details
GET  /about                      # About page
GET  /concert1-8                 # Concert detail pages
```

### Authentication (Protected by `guest` middleware)

```
GET/POST  /register              # User registration
GET/POST  /login                 # User login
POST      /logout                # Logout (requires auth)
```

### User Routes (Protected by `auth`)

```
GET  /dashboard                  # Role-based dashboard
GET  /events                     # Browse events
GET  /orders                     # My orders
GET  /orders/{order}             # Order details
GET  /my-tickets                 # My tickets
GET  /tickets/{ticket}           # Ticket view
GET  /tickets/{ticket}/qr        # Download QR code
```

### Organizer Routes (Protected by `auth` + `organizer`)

```
GET/POST  /organizer/events/create              # Create event
PUT       /organizer/events/{event}             # Update event
DELETE    /organizer/events/{event}             # Delete event
POST      /organizer/events/{event}/publish     # Publish event

GET/POST  /organizer/events/{event}/tickets     # Manage ticket categories
```

### Payment Routes

```
GET/POST  /orders/{order}/payment               # Payment page & processing
GET       /orders/{order}/payment/verify        # Payment verification
POST      /orders/{order}/refund                # Refund order
```

### Admin Routes (Protected by `auth` + `admin`)

```
GET  /admin/users                # Manage users
GET  /admin/analytics            # Analytics dashboard
GET  /admin/reports              # Generate reports
```

---

## Controllers Overview

### AuthController

- `showRegister()` / `register()` - Handle user registration
- `showLogin()` / `login()` - Handle user login
- `logout()` - Handle logout

### DashboardController

- `index()` - Route to role-specific dashboard
- `adminDashboard()` - Admin stats and insights
- `organizerDashboard()` - Organizer stats
- `userDashboard()` - User stats

### EventController

- `index()` - List published events
- `show()` - Event details
- `create()`, `store()` - Create event
- `edit()`, `update()` - Update event
- `destroy()` - Delete event
- `publish()` - Publish draft event

### OrderController

- `create()` - Order form
- `store()` - Create order & generate tickets
- `show()` - Order details
- `index()` - User orders list
- `cancel()` - Cancel order & refund

### PaymentController

- `show()` - Payment page
- `process()` - Process payment
- `verify()` - Verify payment status
- `refund()` - Refund payment

### TicketController

- `generateQrCode()` - Generate QR code
- `download()` - Download ticket
- `view()` - View ticket details
- `validate()` - Validate/scan ticket
- `myTickets()` - List user tickets

### TicketCategoryController

- `index()`, `create()`, `store()` - Manage ticket categories
- `edit()`, `update()` - Update category
- `destroy()` - Delete category

### AdminDashboardController

- `stats()` - System statistics
- `analytics()` - Revenue analytics
- `topEvents()` - Top performing events
- `revenueByCategory()` - Revenue breakdown

---

## Models & Relationships

```
User
├── Events (organizer_id)
├── Orders
├── Transactions
└── WaitingLists

Event
├── Organizer (User)
├── TicketCategories
├── Orders
├── Tickets
└── WaitingLists

TicketCategory
├── Event
├── Tickets
└── WaitingLists

Order
├── User
├── Event
├── Tickets
└── Transactions

Ticket
├── Order
├── Event
└── TicketCategory

Transaction
├── Order
└── User

WaitingList
├── User
├── Event
└── TicketCategory
```

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php
│   │   ├── DashboardController.php
│   │   ├── EventController.php
│   │   ├── TicketCategoryController.php
│   │   ├── OrderController.php
│   │   ├── PaymentController.php
│   │   ├── TicketController.php
│   │   └── AdminDashboardController.php
│   ├── Middleware/
│   │   ├── CheckRole.php
│   │   ├── EnsureUserIsAdmin.php
│   │   └── EnsureUserIsOrganizer.php
│   └── Policies/
│       ├── EventPolicy.php
│       └── OrderPolicy.php
├── Models/
│   ├── User.php (extended)
│   ├── Event.php
│   ├── TicketCategory.php
│   ├── Order.php
│   ├── Ticket.php
│   ├── Transaction.php
│   └── WaitingList.php
└── Providers/
    └── AuthServiceProvider.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_03_18_000001_update_users_add_role.php
│   ├── 2026_03_18_000002_create_events_table.php
│   ├── 2026_03_18_000003_create_ticket_categories_table.php
│   ├── 2026_03_18_000004_create_orders_table.php
│   ├── 2026_03_18_000005_create_tickets_table.php
│   ├── 2026_03_18_000006_create_transactions_table.php
│   └── 2026_03_18_000007_create_waiting_lists_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/
├── views/
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── dashboard/
│   │   ├── admin.blade.php
│   │   ├── organizer.blade.php
│   │   └── user.blade.php
│   ├── events/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── orders/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── show.blade.php
│   ├── payments/
│   │   └── show.blade.php
│   ├── tickets/
│   │   ├── view.blade.php
│   │   └── my-tickets.blade.php
│   └── layouts/
│       ├── master.blade.php
│       └── ...

routes/
└── web.php (comprehensive routing)

storage/
└── app/
    └── public/
        ├── qr-codes/       # QR code images
        ├── events/         # Event images
        └── avatars/        # User avatars
```

---

## Testing the Platform

### 1. Register & Login

**Test Accounts:**

```
Admin:
  Email: admin@test.com
  Password: admin123!
  Role: admin

Organizer:
  Email: organizer@test.com
  Password: organizer123!
  Role: organizer

User:
  Email: user@test.com
  Password: user123!
  Role: user
```

### 2. Test Event Creation (Organizer)

1. Login as organizer
2. Dashboard → Create Event
3. Fill event details
4. Add ticket categories (VIP, Regular, etc.)
5. Publish event

### 3. Test Ticket Purchase (User)

1. Login as user
2. Browse events → Select event
3. Choose ticket type and quantity
4. Create order (pending)
5. Go to payment → Pay
6. Order marked as "paid"
7. Tickets generated with QR codes

### 4. Test Ticket Validation

1. User downloads ticket with QR code
2. Event staff scans QR code
3. System validates and marks as "used"
4. Validation recorded with timestamp and staff name

### 5. Test Waiting List

1. Event with limited tickets
2. All tickets sold out
3. User attempts to purchase → Offer waiting list
4. User joins waiting list (position assigned)
5. When ticket becomes available, notify user
6. User accepts → Order created

---

## Payment Simulation

**Dummy Gateway Configuration:**

```php
// In PaymentController::simulatePayment()

// Success conditions (95% of cases):
- Any valid card number (13-19 digits)
- Not ending in "00"

// Failure conditions (5% of cases):
- Card numbers ending in "00"
- These will be marked as failed

// Test Cards:
4111 1111 1111 1111  → Success
4000 0000 0000 0002  → Failure
```

**Transaction Flow:**

```
1. Create Transaction (pending)
2. Simulate payment processing (500ms delay)
3. Check success criteria
4. Mark as completed/failed
5. Update Order status
6. Send notification
7. Generate e-ticket
```

---

## Email Notifications

**Notification Types:**

```php
// PaymentConfirmation - After successful payment
// OrderConfirmation - When order created
// TicketReady - When e-ticket generated
// WaitingListNotification - When position available
// TicketValidation - When ticket scanned
```

**Configuration (Update in .env):**

```env
MAIL_MAILER=log          # Use log driver for testing
MAIL_FROM_ADDRESS=noreply@tixly.com
```

**Testing Emails:**

- Log driver: Check `storage/logs/laravel.log`
- For real emails: Change to SMTP config

---

## Security Considerations

✅ **Implemented:**

- Password hashing (bcrypt/argon2)
- CSRF token protection
- Authorization policies
- Role-based middleware
- SQL injection protection (Eloquent ORM)
- Session management
- Email verification (Laravel default)

⚠️ **TODO for Production:**

- Rate limiting on payment endpoint
- Two-factor authentication (2FA)
- API token authentication
- Event audience/category permissions
- Refund policy validation
- PCI DSS compliance for real payments

---

## Performance Optimization Tips

1. **Database Indexing:**
    - Already indexed: user_id, event_id, status fields
    - Add more as needed for large datasets

2. **Caching:**

    ```php
    // Cache event listings
    $events = Cache::remember('events.upcoming', 3600, fn() =>
        Event::published()->upcoming()->get()
    );
    ```

3. **Pagination:**
    - Use Laravel's built-in pagination
    - Default 15 items per page

4. **Query Optimization:**
    - Use `with()` to eagerly load relations
    - Use `select()` to fetch only needed columns
    - Use database-level aggregation

5. **Queue Jobs:**
    - Email sending (optional)
    - PDF generation for reports
    - Update waiting lists

---

## Troubleshooting

### Issue: Migrations not running

```bash
php artisan migrate:fresh   # Drop and re-run
php artisan migrate:rollback # Rollback last batch
```

### Issue: Storage files not accessible

```bash
php artisan storage:link    # Create symlink
chmod -R 755 storage/app/public
```

### Issue: QR codes not generating

```bash
# Install package:
composer require simplesoftware/simple-qrcode

# Clear cache:
php artisan config:cache
```

### Issue: Permission denied errors

```bash
# Fix permissions:
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## Next Steps & Enhancements

### Phase 2 Features:

- [ ] Real payment gateway integration (Stripe, PayPal)
- [ ] SMS notifications
- [ ] Mobile app (Flutter/React Native)
- [ ] Advanced analytics dashboard
- [ ] Event seating map
- [ ] Refund management system
- [ ] Review/rating system
- [ ] Promo codes and discounts
- [ ] Multi-language support
- [ ] Dark mode

### Integration Improvements:

- Real-time notifications using WebSockets
- Email service integration (SendGrid, AWS SES)
- Cloud storage (AWS S3)
- Analytics service (Google Analytics)
- Payment gateway (Stripe, PayPal, Midtrans for Indonesia)

---

## Support & Documentation

- Laravel Docs: https://laravel.com/docs/12.x
- Bootstrap Docs: https://getbootstrap.com/docs/5.0
- QR Code Package: https://github.com/SimpleSoftwareIO/simple-qrcode

---

**Version:** 1.0
**Last Updated:** March 18, 2026
**Status:** Development Ready
