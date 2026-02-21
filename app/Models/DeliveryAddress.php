<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryAddress extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'customer_name',
        'phone',
        'address_line',
        'city',
    ];

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'address_id');
    }
}
