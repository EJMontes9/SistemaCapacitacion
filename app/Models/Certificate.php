<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['course_id', 'user_id', 'completion_date', 'certificate_code', 'grade', 'status', 'issued_by', 'metadata', 'body_html'];

    protected $casts = ['completion_date' => 'date', 'metadata' => 'json'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public static function generateCode($courseId, $userId)
    {
        return 'CERT-' . strtoupper(substr(md5($courseId . '-' . $userId . time()), 0, 8)) . '-' . $courseId . '-' . $userId;
    }
}
