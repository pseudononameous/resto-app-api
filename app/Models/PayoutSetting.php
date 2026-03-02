<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutSetting extends Model
{
    protected $table = 'payout_settings';

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_name',
        'account_number',
        'schedule',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

