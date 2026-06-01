<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $table = 'courses';

    protected $guarded = ['id', 'status'];

    use HasFactory;

    const BORRADOR = 1;

    const REVISION = 2;

    const PUBLICADO = 3;

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requirements()
    {
        return $this->hasMany(requirement::class);
    }

    public function goals()
    {
        return $this->hasMany(goal::class);
    }

    public function audiences()
    {
        return $this->hasMany(audience::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'course_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(review::class);
    }

    public function level()
    {
        return $this->belongsTo(level::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function image()
    {
        return $this->morphOne(image::class, 'imageable');
    }

    public function lessons()
    {
        return $this->hasManyThrough(lesson::class, section::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }

    public function quota()
    {
        return $this->hasOne(CourseQuota::class);
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class);
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    public function modalidad()
    {
        return $this->belongsTo(CatalogItem::class, 'modalidad_id');
    }

    public function periodo()
    {
        return $this->belongsTo(CatalogItem::class, 'periodo_id');
    }

    public function sede()
    {
        return $this->belongsTo(CatalogItem::class, 'sede_id');
    }

    public function nivel()
    {
        return $this->belongsTo(CatalogItem::class, 'nivel_id');
    }
}
