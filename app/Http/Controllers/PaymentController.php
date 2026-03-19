<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function show(Order $order)
    {
        // Simple manual authorization or use Policy
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

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
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:credit_card,bank_transfer,dummy'],
            'card_number' => ['required_if:payment_method,credit_card', 'nullable', 'regex:/^[0-9]{13,19}$/'],
            'card_holder' => ['required_if:payment_method,credit_card', 'nullable', 'string'],
            'cvv' => ['required_if:payment_method,credit_card', 'nullable', 'regex:/^[0-9]{3,4}$/'],
            'bank_name' => ['required_if:payment_method,bank_transfer', 'nullable', 'string'],
            'accept_terms' => ['required', 'accepted'],
        ]);

        return DB::transaction(function () use ($validated, $order, $request) {
            // Create payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'transaction_id' => 'TX-' . strtoupper(Str::random(12)),
            ]);

            // Simulate payment processing
            if ($this->simulatePayment($validated)) {
                $payment->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);
                $order->markAsPaid();

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment successful! Your tickets are ready.');
            } else {
                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'failed']);

                return back()->with('error', 'Payment failed. Please try again or use a different payment method.');
            }
        });
    }

    /**
     * Simulate payment processing
     */
    private function simulatePayment(array $data): bool
    {
        // Simulate payment with 95% success rate
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
        $paid = $order->payments()->where('status', 'success')->exists();

        if ($paid) {
            return response()->json(['status' => 'success', 'message' => 'Payment verified']);
        }

        return response()->json(['status' => 'pending', 'message' => 'Payment pending'], 202);
    }
}
