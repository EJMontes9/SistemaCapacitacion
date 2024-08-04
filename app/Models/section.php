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
    public function lessons()
    {
        return $this->hasMany('App\Models\lessons');
    }

    //Relacion uno a muchos inversa
    public function courses()
    {
        return $this->belongsTo('App\Models\courses');
    }

    public function completedStudents()
    {
        return $this->hasManyThrough(
            User::class,
            Lesson::class,
            'section_id', // Clave foránea en la tabla de lecciones
            'id', // Clave foránea en la tabla de usuarios
            'id', // Clave local en la tabla de secciones
            'id' // Clave local en la tabla de usuarios
        )->join('lesson_user', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->select('users.id')
            ->distinct()
            ->count();
    }

}
