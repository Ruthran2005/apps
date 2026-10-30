<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'audience',
        'priority',
        'author_name',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}
