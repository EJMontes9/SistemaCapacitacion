<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{

    protected $fillable = ['name'];

    use HasFactory;

    //Relacion uno a muchos
    public function courses(){
        return $this->hasMany('App\Models\courses');
    }
}
