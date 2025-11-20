<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'image',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'translated_title',
        'translated_subtitle',
        'translated_description',
    ];

    /**
     * Relations to translations
     */
    public function translations(): HasMany
    {
        return $this->hasMany(AboutUsTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(AboutUsTranslation::class)->where('locale', app()->getLocale());
    }

    /**
     * Locale-aware accessors with fallback to RU then first available
     */
    public function getTranslatedTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->title;
    }

    public function getTranslatedSubtitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->subtitle;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->description;
    }
}
