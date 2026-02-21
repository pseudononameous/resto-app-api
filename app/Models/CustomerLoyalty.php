<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLoyalty extends Model
{
    public $timestamps = false;

    protected $table = 'customer_loyalty';

    protected $fillable = [
        'customer_id',
        'points_balance',
        'lifetime_points',
        'tier',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
