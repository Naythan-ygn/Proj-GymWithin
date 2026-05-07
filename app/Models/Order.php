<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'shipping_address',
        'status',
        'payment_status',
        'payment_reviewed_at',
        'payment_notification_seen_at',
        'payment_notes',
        'payment_method',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'payment_method' => 'string',
        'payment_reviewed_at' => 'datetime',
        'payment_notification_seen_at' => 'datetime',
    ];
    /**
     * Define the relationship to the User (Customer)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to the Order Items
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
