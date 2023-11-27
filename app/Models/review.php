<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class review extends Model
{
    protected $guarded = ['id'];
    use HasFactory;

    //Relacion uno a muchos inversa
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    //Relacion uno a muchos inversa
    public function course(){
        return $this->belongsTo('App\Models\courses');
    }

}
