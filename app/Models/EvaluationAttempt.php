<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationAttempt extends Model
{
    protected $fillable = ['evaluation_id', 'user_id', 'attempt_number', 'started_at', 'finished_at', 'score', 'passed', 'answers'];

    protected $casts = ['answers' => 'array', 'started_at' => 'datetime', 'finished_at' => 'datetime'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
