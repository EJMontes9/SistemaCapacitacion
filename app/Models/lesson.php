<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lesson extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    //relacion uno a uno
    public function description(){
        return $this->hasOne('App\Models\description');
    }


    public function section(){
        return $this->belongsTo('App\Models\section');
    }

    public function platform(){
        return $this->belongsTo('App\Models\platforms');
    }

    //Relacion muchos a muchos
    public function users(){
        return $this->belongsToMany('App\Models\User');
    }

    //Relacion uno a uno polimorfica
    public function resource(){
        return $this->morphOne('App\Models\resource','resourceable');
    }

    //Relacion uno a muchos polimorfica
    public function comments(){
        return $this->morphMany('App\Models\comment','commentable');
    }

    //Relacion uno a uno polimorfica
    public function reactions(){
        return $this->morphMany('App\Models\reaction','reactionable');
    }


}
