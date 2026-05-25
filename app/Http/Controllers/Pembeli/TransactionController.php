<?php
namespace App\Http\Controllers\Pembeli;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Services\MidtransService;

class TransactionController extends Controller
{
    public function checkout(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'pending', 403);
        $booking->load('property.cover');
        return view('pembeli.checkout', compact('booking'));
    }

    public function pay(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'pending', 403);

        $existing = Transaction::where('booking_id', $booking->id)
            ->where('status', 'pending')->first();
        if ($existing) {
            return redirect()->route('pembeli.payment', $existing);
        }

        $transaction = Transaction::create([
            'invoice_number'    => Transaction::generateInvoice(),
            'user_id'           => auth()->id(),
            'property_id'       => $booking->property_id,
            'booking_id'        => $booking->id,
            'type'              => 'rent',
            'midtrans_order_id' => 'ORDER-'.$booking->id.'-'.time(),
            'amount'            => $booking->total_price + $booking->deposit_paid,
            'status'            => 'pending',
        ]);

        return redirect()->route('pembeli.payment', $transaction);
    }

    public function payment(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        $transaction->load('property','booking');
        $snapToken = (new MidtransService())->createSnapToken($transaction);
        return view('pembeli.payment', compact('transaction','snapToken'));
    }

    public function success(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        $transaction->load('property.cover','booking');
        return view('pembeli.payment_success', compact('transaction'));
    }
}
