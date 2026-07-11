<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'image',
        'visit_url',
        'github_url',
        'tags',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
    ];
}