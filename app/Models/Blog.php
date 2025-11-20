<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'image',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'translated_title',
        'translated_description',
        'translated_slug',
        'translated_seo_title',
        'translated_seo_description',
    ];


    function category() : BelongsTo {
       return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id'); 
    }

    function author() : BelongsTo{
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }


    function comments() : HasMany {
        return $this->hasMany(BlogComment::class, 'blog_id', 'id');
    }

    /**
     * Relations to translations
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(BlogTranslation::class)->where('locale', app()->getLocale());
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

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->description;
    }

    public function getTranslatedSlugAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->slug;
    }

    public function getTranslatedSeoTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->seo_title ?? $this->getTranslatedTitleAttribute();
    }

    public function getTranslatedSeoDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->seo_description;
    }
}
