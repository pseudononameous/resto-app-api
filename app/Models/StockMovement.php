<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'reference_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (StockMovement $model) {
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
