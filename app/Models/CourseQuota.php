<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseQuota extends Model
{
    protected $fillable = ['course_id', 'max_size', 'used_size', 'max_files'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'course_id');
    }

    public function hasSpaceFor($fileSize)
    {
        return ($this->used_size + $fileSize) <= $this->max_size;
    }

    public function addSize($fileSize)
    {
        $this->increment('used_size', $fileSize);
    }

    public function removeSize($fileSize)
    {
        $this->decrement('used_size', $fileSize);
    }
}
