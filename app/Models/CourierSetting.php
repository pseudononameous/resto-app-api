<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierSetting extends Model
{
    protected $table = 'courier_settings';

    protected $fillable = [
        'user_id',
        'own_riders',
        'third_party_couriers',
        'notes',
    ];

    protected $casts = [
        'own_riders' => 'bool',
        'third_party_couriers' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

