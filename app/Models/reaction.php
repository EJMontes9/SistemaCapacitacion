<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reaction extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    const LIKE = 1;
    const DISLIKE = 2;

    public function reactionable(){
        return $this->morphTo();
    }

    //relacion una a muchas inversa
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
