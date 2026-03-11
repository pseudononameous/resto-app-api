<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Alias relationship required for scoped route model binding on:
     * DELETE /v1/carts/{cart}/items/{cart_item}
     *
     * Laravel expects a relation named `cartItems` on the Cart model
     * when scoping the {cart_item} parameter, so we forward this to `items()`.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}

