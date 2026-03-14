<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
