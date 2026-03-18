<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order)->with('info', 'This order is already paid.');
        }

        return view('payments.show', compact('order'));
    }

    /**
     * Process payment (dummy gateway)
     */
    public function process(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:credit_card,bank_transfer,dummy'],
            'card_number' => ['required_if:payment_method,credit_card', 'nullable', 'regex:/^[0-9]{13,19}$/'],
            'card_holder' => ['required_if:payment_method,credit_card', 'nullable', 'string'],
            'cvv' => ['required_if:payment_method,credit_card', 'nullable', 'regex:/^[0-9]{3,4}$/'],
            'bank_name' => ['required_if:payment_method,bank_transfer', 'nullable', 'string'],
            'accept_terms' => ['required', 'accepted'],
        ]);

        return DB::transaction(function () use ($validated, $order, $request) {
            // Create transaction
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'amount' => $order->total_price,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'notes' => $request->input('notes'),
            ]);

            // Simulate payment processing
            if ($this->simulatePayment($validated)) {
                $transaction->markAsCompleted();
                $order->markAsPaid();

                // Send confirmation email
                \App\Notifications\PaymentConfirmation::dispatch($order);

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment successful! Your tickets are ready.');
            } else {
                $transaction->markAsFailed();

                return back()->with('error', 'Payment failed. Please try again or use a different payment method.');
            }
        });
    }

    /**
     * Simulate payment processing
     */
    private function simulatePayment(array $data): bool
    {
        // Simulate payment with 95% success rate (only fail for test card numbers ending in 00)
        if ($data['payment_method'] === 'credit_card') {
            $cardNumber = $data['card_number'] ?? '';
            if (substr($cardNumber, -2) === '00') {
                return false;
            }
        }

        // Simulate processing time
        usleep(500000); // 0.5 seconds

        return true;
    }

    /**
     * Verify payment (webhook simulation)
     */
    public function verify(Order $order)
    {
        $transaction = $order->transactions()->where('status', 'completed')->latest()->first();

        if ($transaction) {
            return response()->json(['status' => 'success', 'message' => 'Payment verified']);
        }

        return response()->json(['status' => 'pending', 'message' => 'Payment pending'], 202);
    }

    /**
     * Refund payment
     */
    public function refund(Order $order)
    {
        $this->authorize('view', $order);

        if (!$order->isPaid()) {
            return back()->with('error', 'Only paid orders can be refunded.');
        }

        return DB::transaction(function () use ($order) {
            // Get the paid transaction
            $transaction = $order->transactions()->where('status', 'completed')->latest()->first();

            if ($transaction) {
                $transaction->refund();
            }

            // Cancel order
            $order->cancel();

            // Refund tickets back to category
            foreach ($order->tickets as $ticket) {
                $ticket->ticketCategory->increaseAvailableTickets(1);
                $ticket->update(['status' => 'cancelled']);
            }

            return back()->with('success', 'Order refunded successfully.');
        });
    }
}
