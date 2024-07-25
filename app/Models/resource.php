<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'lesson_id',
        'type'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}