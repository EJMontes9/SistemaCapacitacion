<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\courses;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['instructor_id', 'course_id', 'title', 'description', 'module_id'];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function module()
    {
        return $this->belongsTo(Section::class);
    }
}
