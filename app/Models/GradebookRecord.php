<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradebookRecord extends Model
{
    protected $fillable = ['course_id', 'user_id', 'evaluation_id', 'section_id', 'type', 'score', 'max_score', 'weight', 'notes', 'created_by'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
