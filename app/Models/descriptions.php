<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class descriptions extends Model
{

    protected $guarded = ['id'];
    use HasFactory;

    //relacion uno a uno inversa
    public function lesson(){
        return $this->belongsTo('App\Models\lesson');
    }
}
