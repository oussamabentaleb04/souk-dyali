<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];

    protected $fillable = [
        'buyer_id', 'seller_profile_id', 'status', 'subtotal', 'total',
        'shipping_address', 'phone', 'notes',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function sellerProfile()
    {
        return $this->belongsTo(SellerProfile::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}