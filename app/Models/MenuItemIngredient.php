<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemIngredient extends Model
{
    public $timestamps = false;

    protected $fillable = ['menu_item_id', 'product_id', 'quantity_per_serving'];

    protected function casts(): array
    {
        return ['quantity_per_serving' => 'decimal:4'];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
