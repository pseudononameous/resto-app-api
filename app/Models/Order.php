<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function orderType() { return $this->belongsTo(OrderType::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function store() { return $this->belongsTo(Store::class); }
    public function items() { return $this->hasMany(OrderItem::class, 'order_id'); }
    public function delivery() { return $this->hasOne(Delivery::class); }
    public function kitchenTicket() { return $this->hasOne(KitchenTicket::class); }
}

