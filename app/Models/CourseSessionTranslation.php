<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSessionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_session_id',
        'locale',
        'description',
    ];

    public function courseSession(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class);
    }
}
