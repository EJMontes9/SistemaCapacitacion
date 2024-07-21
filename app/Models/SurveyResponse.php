<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = ['survey_id', 'user_id', 'lesson_id', 'response_text', 'response_number'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getResponseAttribute()
    {
        return $this->response_number ?? $this->response_text;
    }

    public function setResponseAttribute($value)
    {
        if (is_numeric($value)) {
            $this->attributes['response_number'] = $value;
        } else {
            $this->attributes['response_text'] = $value;
        }
    }
}