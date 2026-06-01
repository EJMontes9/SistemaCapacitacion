<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['options', 'correct_answer', 'question_id', 'optionable_id', 'optionable_type'];

    protected $attributes = [
        'correct_answer' => false,
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function optionable()
    {
        return $this->morphTo();
    }
}
