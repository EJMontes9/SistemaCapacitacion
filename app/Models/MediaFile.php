<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'user_id', 'title', 'description', 'original_name', 'filename', 'path',
        'type', 'mime_type', 'size', 'disk', 'thumbnail_path',
        'is_compressed', 'downloads_count',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path
            ? Storage::disk($this->disk)->url($this->thumbnail_path)
            : null;
    }

    public function getSizeFormattedAttribute()
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
