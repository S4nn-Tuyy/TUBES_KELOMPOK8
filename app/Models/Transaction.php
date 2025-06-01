<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'product_id',
        'product_name',
        'quantity',
        'total_price',
        'status', // pending, processing, completed, cancelled
        'payment_status',
        'shipping_address'
    ];

    protected $with = ['product', 'buyer', 'seller'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => $this->product_name,
            'price' => $this->quantity > 0 ? ($this->total_price / $this->quantity) : 0,
            'image' => null,
            'exists' => false,
            'id' => $this->product_id
        ]);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
} 