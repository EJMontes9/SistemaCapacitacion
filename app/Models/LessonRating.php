<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonRating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lesson_id', 'survey_id', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}