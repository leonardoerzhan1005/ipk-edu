<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'category',
        'title',
        'description',
        'file_path',
        'file_format',
        'file_size',
        'published_at',
        'status',
    ];

    protected $casts = [
        'published_at' => 'date',
        'status' => 'boolean',
    ];
}


