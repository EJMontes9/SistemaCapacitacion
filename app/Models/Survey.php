<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'target_type',
        'target_id',
        'has_yes_no',
        'has_rating',
        'has_comment'
    ];

    protected $casts = [
        'has_yes_no' => 'boolean',
        'has_rating' => 'boolean',
        'has_comment' => 'boolean',
    ];
}