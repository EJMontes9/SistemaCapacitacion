<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id', 'parent_id', 'title', 'url', 'route', 'icon',
        'permission', 'roles', 'order', 'target', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function isVisible()
    {
        if (!$this->is_active) return false;

        if ($this->roles) {
            $roles = explode(',', $this->roles);
            if (!auth()->user()) return false;
            return auth()->user()->hasAnyRole($roles);
        }

        if ($this->permission) {
            if (!auth()->user()) return false;
            return auth()->user()->can($this->permission);
        }

        return true;
    }

    public function getUrl()
    {
        if ($this->route) return route($this->route, [], false);
        return $this->url ?? '#';
    }
}
