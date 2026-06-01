<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'model_type'];

    public function items()
    {
        return $this->hasMany(CatalogItem::class)->orderBy('order');
    }

    public function activeItems()
    {
        return $this->hasMany(CatalogItem::class)->where('is_active', true)->orderBy('order');
    }

    public static function getItemsBySlug($slug)
    {
        return static::where('slug', $slug)->first()?->activeItems ?? collect();
    }
}
