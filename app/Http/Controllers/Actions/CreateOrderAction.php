<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateOrderAction
{
    public function execute(int $userId, int $eventId, array $items): Order
    {
        return DB::transaction(function () use ($userId, $eventId, $items) {
            $event = Event::findOrFail($eventId);

            $total = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $ticketType = TicketType::where('event_id', $eventId)
                    ->lockForUpdate()
                    ->findOrFail($item['ticket_type_id']);

                // Hitung stok tersedia (quantity_total - quantity_sold - reservasi aktif)
                $available = $ticketType->quantity_total - $ticketType->quantity_sold;
                // Kurangi dengan reservasi aktif (belum diimplementasi, bisa ditambahkan nanti)

                if ($available < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'ticket_type_id' => "Stok tiket {$ticketType->name} tidak mencukupi."
                    ]);
                }

                $subtotal = $ticketType->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $ticketType->price,
                ];
            }

            // Buat order
            $order = Order::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            // Simpan order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // TODO: buat reservasi sementara di tabel ticket_reservations

            return $order;
        });
    }
}