<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['instructor_id', 'course_id', 'title', 'description', 'module_id', 'max_attempts', 'time_limit', 'passing_score', 'allow_retake'];

    protected $casts = [
        'max_attempts' => 'integer',
        'time_limit' => 'integer',
        'passing_score' => 'decimal:2',
        'allow_retake' => 'boolean',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function module()
    {
        return $this->belongsTo(Section::class);
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Section');
    }

    public function attempts()
    {
        return $this->hasMany(EvaluationAttempt::class);
    }

    public function userAttempts($userId)
    {
        return $this->attempts()->where('user_id', $userId)->orderBy('attempt_number');
    }

    public function gradebookRecords()
    {
        return $this->hasMany(GradebookRecord::class);
    }
}
