<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxSetting extends Model
{
    protected $table = 'tax_settings';

    protected $fillable = [
        'user_id',
        'default_rate',
        'inclusive_pricing',
    ];

    protected $casts = [
        'default_rate' => 'float',
        'inclusive_pricing' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

