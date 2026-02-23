<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'batch_id',
        'movement_type',
        'quantity',
        'reference_id',
        'notes',
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

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }
}
