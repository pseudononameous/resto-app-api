<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function ingredients()
    {
        return $this->hasMany(MenuItemIngredient::class)->orderBy('id');
    }
}
