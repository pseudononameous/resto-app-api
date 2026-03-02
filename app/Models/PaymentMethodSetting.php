<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethodSetting extends Model
{
    protected $table = 'payment_method_settings';

    protected $fillable = [
        'user_id',
        'cash',
        'card',
        'wallet',
        'notes',
    ];

    protected $casts = [
        'cash' => 'bool',
        'card' => 'bool',
        'wallet' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

