<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number','user_id','property_id','booking_id',
        'type','midtrans_order_id','midtrans_transaction_id',
        'payment_type','amount','status','midtrans_payload','paid_at',
    ];
    protected $casts = [
        'amount'           => 'decimal:2',
        'midtrans_payload' => 'array',
        'paid_at'          => 'datetime',
    ];
    public function user()     { return $this->belongsTo(User::class); }
    public function property() { return $this->belongsTo(Property::class); }
    public function booking()  { return $this->belongsTo(Booking::class); }

    public static function generateInvoice(): string
    {
        $last = self::latest()->first();
        $num  = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return 'INV-'.date('Ymd').'-'.str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
