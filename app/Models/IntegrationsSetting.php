<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationsSetting extends Model
{
    protected $table = 'integrations_settings';

    protected $fillable = [
        'user_id',
        'zapier',
        'webhooks',
        'pos_webhook_url',
    ];

    protected $casts = [
        'zapier' => 'bool',
        'webhooks' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

