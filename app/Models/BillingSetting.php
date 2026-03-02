<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingSetting extends Model
{
    protected $table = 'billing_settings';

    protected $fillable = [
        'user_id',
        'plan',
        'next_invoice_date',
    ];

    protected $casts = [
        'next_invoice_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

