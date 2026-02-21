<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBatch extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'quantity',
        'prepared_date',
        'expiry_date',
        'prepared_by',
    ];

    protected function casts(): array
    {
        return [
            'prepared_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }
}
