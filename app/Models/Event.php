<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['course_id', 'user_id', 'title', 'description', 'event_date', 'event_time', 'end_date', 'location', 'color', 'type', 'is_all_day'];

    protected $casts = ['event_date' => 'date', 'end_date' => 'date', 'is_all_day' => 'boolean'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
