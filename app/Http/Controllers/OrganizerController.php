<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ──────────────────────────────────────────────
        // DATA DUMMY — Ganti dengan query DB jika events
        // sudah terisi di tabel `events` & `orders`
        // ──────────────────────────────────────────────
        $myEvents = [
            [
                'id'           => 1,
                'name'         => 'Radiohead',
                'emoji'        => '🎸',
                'venue'        => 'Jakarta International Stadium, Indonesia',
                'date'         => '15 June 2025',
                'status_label' => 'Active',
                'status_class' => 'active',
                'total'        => 10000,
                'sold'         => 7430,
                'orders'       => 3215,
                'orders_list'  => [
                    [
                        'order_number' => 'ORD-20250615-0021',
                        'buyer_name'   => 'Budi Santoso',
                        'buyer_email'  => 'budi@email.com',
                        'ticket_type'  => 'VIP Gold',
                        'qty'          => 2,
                        'amount'       => 3400000,
                        'status'       => 'paid',
                        'date'         => '12 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250615-0022',
                        'buyer_name'   => 'Siti Aminah',
                        'buyer_email'  => 'siti@email.com',
                        'ticket_type'  => 'Regular',
                        'qty'          => 4,
                        'amount'       => 2800000,
                        'status'       => 'paid',
                        'date'         => '13 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250615-0023',
                        'buyer_name'   => 'Eko Prasetyo',
                        'buyer_email'  => 'eko@email.com',
                        'ticket_type'  => 'Prestige',
                        'qty'          => 1,
                        'amount'       => 1500000,
                        'status'       => 'pending',
                        'date'         => '14 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250615-0024',
                        'buyer_name'   => 'Dewi Rahayu',
                        'buyer_email'  => 'dewi@email.com',
                        'ticket_type'  => 'Regular',
                        'qty'          => 3,
                        'amount'       => 2100000,
                        'status'       => 'paid',
                        'date'         => '14 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250615-0025',
                        'buyer_name'   => 'Rafi Ahmad',
                        'buyer_email'  => 'rafi@email.com',
                        'ticket_type'  => 'VIP Gold',
                        'qty'          => 2,
                        'amount'       => 3400000,
                        'status'       => 'failed',
                        'date'         => '15 Mar 2025',
                    ],
                ],
            ],
            [
                'id'           => 2,
                'name'         => 'Coldplay',
                'emoji'        => '🌌',
                'venue'        => 'Music of the Spheres World Tour - Jakarta',
                'date'         => '20 August 2025',
                'status_label' => 'Upcoming',
                'status_class' => 'upcoming',
                'total'        => 50000,
                'sold'         => 31200,
                'orders'       => 14800,
                'orders_list'  => [
                    [
                        'order_number' => 'ORD-20250820-0101',
                        'buyer_name'   => 'Ahmad Fauzi',
                        'buyer_email'  => 'ahmad@email.com',
                        'ticket_type'  => 'Yellow Bracelet',
                        'qty'          => 2,
                        'amount'       => 4600000,
                        'status'       => 'paid',
                        'date'         => '10 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250820-0102',
                        'buyer_name'   => 'Maya Putri',
                        'buyer_email'  => 'maya@email.com',
                        'ticket_type'  => 'CAT 1',
                        'qty'          => 3,
                        'amount'       => 7200000,
                        'status'       => 'paid',
                        'date'         => '11 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250820-0103',
                        'buyer_name'   => 'Hendra Wijaya',
                        'buyer_email'  => 'hendra@email.com',
                        'ticket_type'  => 'Festival',
                        'qty'          => 5,
                        'amount'       => 6250000,
                        'status'       => 'paid',
                        'date'         => '12 Mar 2025',
                    ],
                    [
                        'order_number' => 'ORD-20250820-0104',
                        'buyer_name'   => 'Lisa Maulana',
                        'buyer_email'  => 'lisa@email.com',
                        'ticket_type'  => 'Yellow Bracelet',
                        'qty'          => 1,
                        'amount'       => 2300000,
                        'status'       => 'pending',
                        'date'         => '13 Mar 2025',
                    ],
                ],
            ],
        ];

        // Hitung aggregates
        $totalTicketsSold = collect($myEvents)->sum('sold');
        $totalOrders      = collect($myEvents)->sum('orders');
        $totalRevenue     = collect($myEvents)->sum(function ($e) {
            return collect($e['orders_list'])
                ->where('status', 'paid')
                ->sum('amount');
        });

        return view('organizer', compact(
            'myEvents',
            'totalTicketsSold',
            'totalOrders',
            'totalRevenue'
        ));
    }
}
