<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['catalog_id', 'name', 'value', 'code', 'description', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }
}
