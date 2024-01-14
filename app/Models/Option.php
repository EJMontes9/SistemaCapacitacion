<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['options', 'correct_answer', 'question_id'];

    protected $attributes = [
        'correct_answer' => false,
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
