<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    protected $table = 'notification_settings';

    protected $fillable = [
        'user_id',
        'email_orders',
        'email_reports',
        'sms_delivery_updates',
        'in_app_kitchen_alerts',
    ];

    protected $casts = [
        'email_orders' => 'bool',
        'email_reports' => 'bool',
        'sms_delivery_updates' => 'bool',
        'in_app_kitchen_alerts' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

