<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorProfileTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_profile_id',
        'locale',
        'title',
        'position',
        'short_bio',
        'bio',
        'achievements',
        'highlights',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(InstructorProfile::class, 'instructor_profile_id');
    }
}


