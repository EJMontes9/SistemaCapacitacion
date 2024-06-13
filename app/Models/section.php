<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section extends Model
{
    protected $fillable = [
        'name',
        'course_id',
    ];
    
    protected $guarded = ['id'];
    use HasFactory;

    //Relacion uno a muchos
    public function lessons(){
        return $this->hasMany('App\Models\lessons');
    }

    //Relacion uno a muchos inversa
    public function courses(){
        return $this->belongsTo('App\Models\courses');
    }
}
