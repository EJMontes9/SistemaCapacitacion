<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = ['assignment_id', 'user_id', 'text_content', 'file_path', 'file_name', 'file_size', 'score', 'feedback', 'submitted_at'];

    protected $casts = ['submitted_at' => 'datetime'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
