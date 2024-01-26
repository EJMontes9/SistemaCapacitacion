<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'module_id', 'evaluation_id', 'total_score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
