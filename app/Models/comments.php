<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    public function commentable(){
        return $this->morphTo();
    }

    //Relacion uno a muchos polimorfica
    public function comments(){
        return $this->morphMany('App\Models\comment','commentable');
    }

    //Relacion uno a uno polimorfica
    public function reactions(){
        return $this->morphMany('App\Models\reaction','reactionable');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
