<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'website_id',
        'session_token',
        'transaction_uuid',
        'order_id',
        'amount',
        'currency',
        'gateway',
        'status',
        'customer_name',
        'customer_email',
        'return_url',
        'cancel_url',
        'gateway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}