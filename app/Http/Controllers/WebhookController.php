<?php
namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Services\MidtransService;

class WebhookController extends Controller
{
    public function midtrans()
    {
        $result = (new MidtransService())->handleWebhook();

        $transaction = Transaction::where(
            'midtrans_order_id', $result['order_id']
        )->first();

        if (!$transaction) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $transaction->update([
            'status'                  => $result['status'],
            'payment_type'            => $result['payment_type'],
            'midtrans_transaction_id' => $result['transaction_id'],
            'midtrans_payload'        => $result['raw'],
            'paid_at' => $result['status'] === 'settlement' ? now() : null,
        ]);

        if ($result['status'] === 'settlement' && $transaction->booking) {
            $transaction->booking->update(['status' => 'confirmed']);
        }

        return response()->json(['message' => 'OK']);
    }
}
