<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','title','slug','description','type','status',
        'is_approved','is_featured','price','price_monthly',
        'price_yearly','deposit','city','province','address',
        'bedrooms','bathrooms','land_area','building_area',
        'garages','electricity','certificate','virtual_tour_url',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured'  => 'boolean',
        'price'        => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($property) {
            $property->slug = str()->slug($property->title).'-'.uniqid();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class);
    }

    public function cover()
    {
        return $this->hasOne(PropertyMedia::class)->where('is_cover', true);
    }

    public function scopeApproved($q)  { return $q->where('is_approved', true); }
    public function scopeAvailable($q) { return $q->whereIn('status', ['dijual','disewakan']); }
}
