<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Add this array to allow these fields to be saved via OrderItem::create()
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Optional: Relationship back to the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
