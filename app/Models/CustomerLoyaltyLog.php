<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLoyaltyLog extends Model
{
    public $timestamps = false;

    protected $table = 'customer_loyalty_logs';

    protected $fillable = [
        'customer_id',
        'points',
        'type',
        'reference_order_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (CustomerLoyaltyLog $model) {
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
