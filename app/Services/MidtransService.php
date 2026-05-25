<?php
namespace App\Services;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(Transaction $transaction): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->midtrans_order_id,
                'gross_amount' => (int) $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email'      => $transaction->user->email,
            ],
            'item_details' => [[
                'id'       => $transaction->property_id,
                'price'    => (int) $transaction->amount,
                'quantity' => 1,
                'name'     => substr($transaction->property->title, 0, 50),
            ]],
            'enabled_payments' => [
                'credit_card','bca_va','bni_va','bri_va',
                'gopay','shopeepay','indomaret','alfamart',
            ],
            'expiry' => ['unit' => 'hours', 'duration' => 24],
        ];
        return Snap::getSnapToken($params);
    }

    public function handleWebhook(): array
    {
        $n = new Notification();
        $status = match(true) {
            $n->transaction_status === 'capture'
                && $n->fraud_status === 'accept' => 'settlement',
            $n->transaction_status === 'settlement' => 'settlement',
            in_array($n->transaction_status,
                ['deny','cancel','expire']) => $n->transaction_status,
            default => 'pending',
        };
        return [
            'order_id'       => $n->order_id,
            'status'         => $status,
            'payment_type'   => $n->payment_type,
            'transaction_id' => $n->transaction_id,
            'raw'            => (array) $n,
        ];
    }
}
