<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id','user_id','start_date','end_date',
        'period','total_price','deposit_paid','status','notes',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'total_price' => 'decimal:2',
    ];

    public function property() { return $this->belongsTo(Property::class); }
    public function tenant()   { return $this->belongsTo(User::class, 'user_id'); }

    public static function calculatePrice(Property $property, string $period, string $startDate, string $endDate): float
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end   = \Carbon\Carbon::parse($endDate);

        if ($period === 'monthly') {
            $months = $start->diffInMonths($end) ?: 1;
            return ($property->price_monthly ?? $property->price) * $months;
        }

        $years = $start->diffInYears($end) ?: 1;
        return ($property->price_yearly ?? ($property->price_monthly ?? $property->price) * 12) * $years;
    }

    public static function isAvailable(int $propertyId, string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        $query = self::where('property_id', $propertyId)
            ->whereIn('status', ['pending','confirmed','active'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeId) $query->where('id', '!=', $excludeId);

        return $query->count() === 0;
    }

    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeActive($q)  { return $q->where('status', 'active'); }
}
