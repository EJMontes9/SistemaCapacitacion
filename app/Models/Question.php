<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'evaluation_id', 'score'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
