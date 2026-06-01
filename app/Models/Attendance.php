<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = ['class_session_id', 'user_id', 'status', 'comment'];

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
