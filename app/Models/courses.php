<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{

    protected $guarded = ['id','status'];

    use HasFactory;
    const BORRADOR = 1;
    const REVISION = 2;
    const PUBLICADO = 3;

    //Relacion uno a muchos inversa
    public function teacher(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function requirements(){
        return $this->hasMany('App\Models\requirement');
    }

    public function goals(){
        return $this->hasMany('App\Models\goal');
    }

    public function audiences(){
        return $this->hasMany('App\Models\audience');
    }

    public function sections(){
        return $this->hasMany('App\Models\section');
    }

    //Relacion muchos a muchos
    public function students(){
        return $this->belongsToMany('App\Models\User');
    }

    public function reviews(){
        return $this->hasMany('App/Models/review');
    }

    //Relacion uno a muchos inversa
    public function level(){
        return $this->belongsTo('App\Models\level');
    }

    //Relacion uno a muchos inversa
    public function category(){
        return $this->belongsTo('App\Models\category');
    }

    //Relacion uno a muchos inversa
    public function price(){
        return $this->belongsTo('App\Models\price');
    }

    //Relacion uno a uno polimorfica
    public function image(){
        return $this->morphOne('App\Models\image','imageable');
    }

    public function lessons(){
        return $this->hasManyThrough('App\Models\lesson','App\Models\section');
    }


}
