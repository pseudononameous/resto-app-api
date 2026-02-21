<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reservation_code',
        'customer_id',
        'guest_name',
        'phone',
        'party_size',
        'reservation_date',
        'reservation_time',
        'status',
        'created_by',
        'store_id',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'reservation_time' => 'datetime:H:i',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function store(): BelongsTo { return $this->belongsTo(Store::class); }
    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(DiningTable::class, 'reservation_tables')
            ->withPivot([]);
    }
}
