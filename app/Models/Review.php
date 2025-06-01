<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_id',
        'rating',
        'comment'
    ];

    protected $with = ['user', 'product'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Deleted Product',
            'exists' => false
        ]);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
} 