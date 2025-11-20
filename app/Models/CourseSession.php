<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        "course_id",
        "start_date",
        "end_date",
        "format",
        "is_active",
        "order",
    ];

    protected $casts = [
        "start_date" => "date",
        "end_date" => "date",
        "is_active" => "boolean",
        "order" => "integer",
    ];

    protected $appends = ["translated_description"];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relations to translations
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CourseSessionTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(CourseSessionTranslation::class)->where(
            "locale",
            app()->getLocale(),
        );
    }

    /**
     * Locale-aware accessor with fallback to RU then first available
     */
    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded("translations")
            ? $this->translations
            : $this->translations()->get();
        $t =
            $translations->firstWhere("locale", $locale) ??
            ($translations->firstWhere("locale", "ru") ??
                $translations->first());
        return $t?->description;
    }
}
