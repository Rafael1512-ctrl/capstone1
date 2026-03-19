<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\WaitingList;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // ================== 1. USERS ==================
        // Admin (sudah ada di dump, tapi kita buat tambahan)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Organizers
        $organizers = [];
        $organizerEmails = ['organizer1@example.com', 'organizer2@example.com'];
        foreach ($organizerEmails as $index => $email) {
            $org = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $faker->company,
                    'password' => Hash::make('password'),
                    'role' => 'organizer',
                    'email_verified_at' => now(),
                ]
            );
            $organizers[] = $org;
        }

        // Regular users
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $email = "user{$i}@example.com";
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $faker->name,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]
            );
            $users[] = $user;
        }

        // ================== 2. EVENTS ==================
        $events = [];
        $eventTitles = [
            'Music Festival 2025',
            'Tech Conference 2025',
            'Food & Culinary Expo',
            'Art & Culture Fair',
            'Sport Competition'
        ];

        foreach ($organizers as $org) {
            foreach (array_slice($eventTitles, 0, 2) as $title) {
                $event = Event::create([
                    'organizer_id' => $org->id,
                    'title' => $title . ' - ' . $org->name,
                    'description' => $faker->paragraph(3),
                    'date' => $faker->dateTimeBetween('+1 month', '+6 months'),
                    'location' => $faker->city . ', ' . $faker->streetAddress,
                    'banner_url' => null,
                    'status' => $faker->randomElement(['draft', 'published', 'cancelled']),
                ]);
                $events[] = $event;
            }
        }

        // ================== 3. TICKET TYPES ==================
        $ticketTypes = [];
        foreach ($events as $event) {
            // VIP
            $vip = TicketType::create([
                'event_id' => $event->id,
                'name' => 'VIP',
                'description' => 'Akses VIP + Meet & Greet',
                'price' => $faker->numberBetween(500000, 1000000),
                'quantity_total' => $faker->numberBetween(50, 100),
                'quantity_sold' => 0,
            ]);
            $ticketTypes[] = $vip;

            // Regular
            $reg = TicketType::create([
                'event_id' => $event->id,
                'name' => 'Regular',
                'description' => 'Tiket masuk reguler',
                'price' => $faker->numberBetween(150000, 300000),
                'quantity_total' => $faker->numberBetween(200, 500),
                'quantity_sold' => 0,
            ]);
            $ticketTypes[] = $reg;

            // Early Bird
            if ($faker->boolean(70)) {
                $eb = TicketType::create([
                    'event_id' => $event->id,
                    'name' => 'Early Bird',
                    'description' => 'Promo terbatas',
                    'price' => $faker->numberBetween(100000, 200000),
                    'quantity_total' => $faker->numberBetween(50, 150),
                    'quantity_sold' => 0,
                ]);
                $ticketTypes[] = $eb;
            }
        }

        // ================== 4. ORDERS & RELATED ==================
        $statuses = ['pending', 'paid', 'failed', 'expired'];
        foreach ($users as $user) {
            // Setiap user membuat 1-3 order
            $numOrders = rand(1, 3);
            for ($o = 0; $o < $numOrders; $o++) {
                $event = $faker->randomElement($events);
                $status = $faker->randomElement($statuses);

                // Pilih beberapa ticket types dari event ini
                $eventTicketTypes = TicketType::where('event_id', $event->id)->get();
                if ($eventTicketTypes->isEmpty()) continue;

                $totalAmount = 0;
                $items = [];

                // Pilih 1-3 item
                $numItems = rand(1, min(3, $eventTicketTypes->count()));
                $selectedTypes = $faker->randomElements($eventTicketTypes, $numItems);
                foreach ($selectedTypes as $tt) {
                    $qty = rand(1, 3);
                    $subtotal = $tt->price * $qty;
                    $totalAmount += $subtotal;
                    $items[] = [
                        'ticket_type_id' => $tt->id,
                        'quantity' => $qty,
                        'unit_price' => $tt->price,
                        'subtotal' => $subtotal,
                    ];
                }

                // Buat order
                $order = Order::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'total_amount' => $totalAmount,
                    'status' => $status,
                    'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
                ]);

                // Buat order items
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $item['ticket_type_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                    ]);
                }

                // Jika status paid, buat payment dan tickets
                if ($status === 'paid') {
                    // Payment
                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $totalAmount,
                        'payment_method' => $faker->randomElement(['credit_card', 'bank_transfer', 'dummy']),
                        'status' => 'success',
                        'transaction_id' => 'TRX' . strtoupper(uniqid()),
                        'paid_at' => $faker->dateTimeBetween('-1 month', 'now'),
                    ]);

                    // Update quantity_sold di ticket_types (trigger akan jalan, tapi kita set manual untuk dummy)
                    // Karena trigger sudah ada, sebenarnya tidak perlu set manual. Tapi untuk memastikan data konsisten,
                    // kita update langsung di sini.
                    foreach ($items as $item) {
                        $tt = TicketType::find($item['ticket_type_id']);
                        $tt->increment('quantity_sold', $item['quantity']);
                    }

                    // Generate tickets via trigger atau manual
                    // Trigger sudah otomatis generate, tapi jika trigger belum aktif, kita generate manual
                    // Untuk amannya, kita generate manual karena trigger mungkin belum jalan saat seeder.
                    foreach ($items as $item) {
                        for ($i = 0; $i < $item['quantity']; $i++) {
                            Ticket::create([
                                'order_id' => $order->id,
                                'ticket_type_id' => $item['ticket_type_id'],
                                'unique_code' => (string) \Illuminate\Support\Str::uuid(),
                                'qr_code_url' => null,
                                'is_used' => $faker->boolean(20), // 20% sudah digunakan
                                'used_at' => $faker->boolean(20) ? $faker->dateTimeBetween('-1 week', 'now') : null,
                            ]);
                        }
                    }
                } elseif ($status === 'pending') {
                    // Buat pending payment
                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $totalAmount,
                        'payment_method' => $faker->randomElement(['credit_card', 'bank_transfer']),
                        'status' => 'pending',
                        'transaction_id' => null,
                        'paid_at' => null,
                    ]);
                } elseif ($status === 'failed') {
                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $totalAmount,
                        'payment_method' => $faker->randomElement(['credit_card', 'bank_transfer']),
                        'status' => 'failed',
                        'transaction_id' => 'FAIL' . strtoupper(uniqid()),
                        'paid_at' => null,
                    ]);
                }
            }
        }

        // ================== 5. WAITING LIST ==================
        foreach ($events as $event) {
            // Beberapa ticket types yang sold out (quantity_sold >= quantity_total)
            $soldOutTypes = TicketType::where('event_id', $event->id)
                ->whereRaw('quantity_sold >= quantity_total')
                ->get();
            foreach ($soldOutTypes as $tt) {
                // Tambah 2-5 waiting list
                for ($w = 0; $w < rand(2, 5); $w++) {
                    WaitingList::create([
                        'event_id' => $event->id,
                        'ticket_type_id' => $tt->id,
                        'user_email' => $faker->email,
                        'notified' => $faker->boolean(30),
                    ]);
                }
            }
        }

        // ================== 6. NOTIFICATIONS ==================
        foreach ($users as $user) {
            // Beberapa notifikasi
            for ($n = 0; $n < rand(1, 3); $n++) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $faker->randomElement(['ticket_purchased', 'waiting_list_available', 'event_reminder']),
                    'subject' => $faker->sentence,
                    'message' => $faker->paragraph,
                    'is_sent' => $faker->boolean(80),
                    'sent_at' => $faker->boolean(80) ? $faker->dateTimeBetween('-1 week', 'now') : null,
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
    }
}