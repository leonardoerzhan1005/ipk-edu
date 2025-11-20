<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestimonialTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'testimonial_id',
        'locale',
        'review',
        'user_name',
        'user_title'
    ];

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class, 'testimonial_id');
    }
}