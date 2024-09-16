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
        return $this->hasMany(Lesson::class);
    }

    //Relacion uno a muchos inversa
    public function courses()
    {
        return $this->belongsTo('App\Models\courses');
    }

    public function completedStudents()
    {
        $totalLessonsCount = $this->lessons()->count();

        $completedUsersSubquery = $this->lessons()
            ->join('lesson_user', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->select('lesson_user.user_id')
            ->groupBy('lesson_user.user_id')
            ->havingRaw('COUNT(lesson_user.lesson_id) = ?', [$totalLessonsCount]);

        $completedUsersCount = DB::table(DB::raw("({$completedUsersSubquery->toSql()}) as subquery"))
            ->mergeBindings($completedUsersSubquery->getQuery())
            ->count(DB::raw('DISTINCT subquery.user_id'));

        return $completedUsersCount;
    }

}
