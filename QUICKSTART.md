# LuxTix Platform - Quick Start Guide

## ⚡ 5-Minute Setup

### Step 1: Run Database Migrations

```bash
cd d:\capstone1
php artisan migrate
```

### Step 2: Start Development Server

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: NPM Dev (optional)
npm run dev
```

### Step 3: Access Application

Open your browser and go to: **http://localhost:8000**

---

## 🚀 Quick Test Walk-through

### Part 1: Create an Event (Organizer)

1. **Register as Organizer**
    - Go to home page → Click "Register" or go to `/register`
    - Fill in details:
        - Name: Your Name
        - Email: organizer@example.com
        - Phone: +62 812345678 (optional)
        - Role: Select "Organizer"
        - Password: Pass123!
    - Click "Create Account"

2. **Create an Event**
    - You're redirected to dashboard
    - Click "Create Event" button
    - Fill event details:
        ```
        Title: Coldplay World Tour Jakarta
        Description: Experience Coldplay live...
        Brief Description: Coldplay live concert
        Location: GBK Stadium, Jakarta
        Venue Name: Gelora Bung Karno
        Event Date: 2026-06-15
        Start Time: 19:00
        End Time: 23:00
        Total Capacity: 50000
        Base Price: 50.00 (placeholder)
        Image: Upload (or skip)
        ```
    - Click "Create Event"

3. **Add Ticket Categories**
    - On event page, click "Manage Tickets"
    - Add categories:

        ```
        Category 1:
          Name: VIP
          Price: 150.00
          Total Tickets: 500
          Description: Front row seating

        Category 2:
          Name: Regular
          Price: 75.00
          Total Tickets: 1000

        Category 3:
          Name: Standard
          Price: 50.00
          Total Tickets: 2000
        ```

4. **Publish Event**
    - Go back to event page
    - Click "Publish" button
    - Event now appears to public

---

### Part 2: Buy Tickets (User)

1. **Register as User**
    - Go to Register page
    - Fill in:
        ```
        Name: John Buyer
        Email: user@example.com
        Role: Buyer
        Password: User123!
        ```

2. **Browse Events**
    - Dashboard → Click "Browse All Events"
    - Or go to `/events`
    - Find "Coldplay World Tour Jakarta"
    - Click "View Details"

3. **Purchase Tickets**
    - On event page, click "Buy Tickets"
    - Select ticket type: VIP
    - Select quantity: 2
    - Click "Create Order"
    - You'll see order confirmation with pending status

4. **Complete Payment**
    - On order page, click "Complete Payment"
    - Or go to `/orders/{id}/payment`
    - Select payment method: Dummy (recommended)
    - Enter dummy card info:
        ```
        Card Number: 4111111111111111
        Card Holder: John Buyer
        CVV: 123
        ```
    - Check acceptance box
    - Click "Pay Now"
    - ✅ Payment successful!
    - Order status changes to "Paid"
    - E-tickets automatically generated

5. **Download E-Tickets**
    - Order page shows tickets with "Download" button
    - Click to download ticket with QR code
    - Or go to My Tickets → View/Download individual tickets

---

### Part 3: Validate Tickets (Organizer/Admin)

1. **Login as Organizer**
    - Go to `/login`
    - Email: organizer@example.com
    - Password: Pass123!

2. **Access Ticket Validation**
    - Dashboard → Quick Actions → "Validate Tickets"
    - Or go to `/tickets/validate` (requires POST)

3. **Scan QR Code**
    - Get user's ticket QR code
    - In validation page:
        - Enter ticket number: TKT-YYYYMMDD-XXXXX
        - Click "Validate"
    - ✅ Ticket marked as "Used"
    - Shows validation timestamp and staff name

---

### Part 4: Admin Dashboard

1. **Login as Admin** (optional)
    - Create admin account or modify existing user
    - Set role to "admin" in database
    - Login with admin credentials

2. **Access Admin Dashboard**
    - `/dashboard` → Redirects to admin dashboard
    - See statistics:
        - Total Users
        - Total Organizers
        - Total Events
        - Total Revenue
        - Recent Orders & Transactions

3. **Management Options**
    - "Manage Users" → `/admin/users`
    - "View Analytics" → `/admin/analytics`
    - "Generate Reports" → `/admin/reports`

---

## 🧪 Test Scenarios

### Scenario 1: Test Waiting List

1. Create event with 5 tickets (limited)
2. Register 2 users (User1, User2)
3. User1: Buy 5 tickets → Success (tickets sold out)
4. User2: Try to buy 3 tickets → Option to join waiting list
5. User2: Join waiting list (position: 1)
6. User1: Cancel order → Tickets returned to available
7. User2: Gets notified → Can now complete purchase

### Scenario 2: Test Payment Failure

1. Register user and create order
2. Go to payment page
3. Use card number ending in "00": **4000000000000000**
4. Try to pay → ❌ Payment failed
5. Try again with valid card: **4111111111111111**
6. ✅ Payment successful

### Scenario 3: Test Multiple Events

1. Create 3 events as different organizers
2. Each with different ticket categories
3. Register 3 users and have them buy from different events
4. View admin dashboard to see all activity
5. Generate reports

### Scenario 4: Test Role Permissions

1. Login as User
2. Try to access `/organizer/events/create` → ❌ 403 Forbidden
3. Login as Organizer
4. Access → ✅ Can create events
5. Try to edit another organizer's event → ❌ Unauthorized
6. Login as Admin
7. All access routes → ✅ Can access/manage everything

---

## 📋 Key Routes to Test

| Route                      | Role      | Purpose              |
| -------------------------- | --------- | -------------------- |
| `/`                        | Public    | Home page            |
| `/register`                | Public    | Registration         |
| `/login`                   | Public    | Login                |
| `/dashboard`               | Auth      | Role-based dashboard |
| `/events`                  | Auth      | Browse events        |
| `/events/{event}`          | Auth      | Event details        |
| `/orders`                  | Auth      | My orders            |
| `/orders/{order}`          | Auth      | Order details        |
| `/tickets`                 | Auth      | My tickets           |
| `/tickets/{ticket}`        | Auth      | Ticket details       |
| `/organizer/events/create` | Organizer | Create event         |
| `/admin/users`             | Admin     | Manage users         |
| `/payments.show`           | Auth      | Payment page         |

---

## 🐛 Debugging Tips

### Check Database

```bash
# View users
php artisan tinker
>>> User::all();

# View events
>>> Event::all();

# View orders
>>> Order::all();

# View tickets
>>> Ticket::all();
```

### View Logs

```bash
tail -f storage/logs/laravel.log
```

### Reset Database

```bash
# Re-migrate with fresh data
php artisan migrate:fresh
```

### Clear Cache

```bash
php artisan config:cache
php artisan cache:clear
```

---

## ✅ Checklist - Features to Test

- [ ] User Registration (Buyer & Organizer)
- [ ] User Login / Logout
- [ ] Event Creation (Organizer)
- [ ] Add Ticket Categories
- [ ] Publish Event
- [ ] Browse Events (User)
- [ ] View Event Details
- [ ] Create Order
- [ ] Pending Order Status
- [ ] Payment Processing
- [ ] Order Status Changes to Paid
- [ ] Ticket Generation
- [ ] QR Code Download
- [ ] View My Tickets
- [ ] Ticket Validation/Scanning
- [ ] Waiting List Join
- [ ] Order Cancellation
- [ ] Payment Refund
- [ ] Admin Dashboard
- [ ] Organizer Dashboard
- [ ] User Dashboard
- [ ] Role-based Access Control
- [ ] Policy Authorization

---

## 🎓 Understanding the Architecture

### Authentication Flow

```
User Input → Register/Login Controller → Auth::attempt() / create User →
Redirect to Dashboard → DashboardController checks role →
Load appropriate view (admin/organizer/user)
```

### Order Flow

```
User selects tickets → OrderController::create() →
Check availability → Decrease stock →
Create Order + Tickets → Redirect to payment →
PaymentController::process() → Update status →
Send email → Ready for download
```

### Validation Flow

```
Event Staff scans QR → TicketController::validate() →
Find Ticket by number → Check status →
Mark as used → Record timestamp + staff name →
Return success response
```

---

## 💡 Common Issues & Solutions

**Issue: 403 Unauthorized**

- Check if you're logged in
- Check your user role
- Ensure you're accessing the right route for your role

**Issue: 404 Not Found**

- Run `php artisan migrate`
- Clear cache: `php artisan cache:clear`
- Check route names in routes/web.php

**Issue: Storage files not accessible**

- Run `php artisan storage:link`
- Check storage/app/public/ directory exists

**Issue: Payment always fails**

- Check if card number is valid
- Avoid card numbers ending in "00" (test failure card)
- See PaymentController::simulatePayment()

**Issue: QR codes not generating**

- Install package: `composer require simplesoftware/simple-qrcode`
- Check storage is writable: `chmod 755 storage/`

---

## 📞 Support

If you encounter issues:

1. Check `SETUP_GUIDE.md` for detailed documentation
2. Review error logs: `storage/logs/laravel.log`
3. Check database: `php artisan tinker`
4. Reset everything: `php artisan migrate:fresh`

---

**Ready to test? Let's go!** 🚀

See `SETUP_GUIDE.md` for complete documentation and advanced features.
