<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'user_image'
    ];

    // Связь с переводами
    public function translations(): HasMany
    {
        return $this->hasMany(TestimonialTranslation::class, 'testimonial_id');
    }

    // Геттеры для переводов
    public function getTranslatedReviewAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->review ?? $this->translations()->where('locale', 'ru')->first()?->review;
    }

    public function getTranslatedUserNameAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->user_name ?? $this->translations()->where('locale', 'ru')->first()?->user_name;
    }

    public function getTranslatedUserTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->user_title ?? $this->translations()->where('locale', 'ru')->first()?->user_title;
    }

    // Геттеры для обратной совместимости - всегда используют переводы
    public function getReviewAttribute()
    {
        return $this->getTranslatedReviewAttribute();
    }

    public function getUserNameAttribute()
    {
        return $this->getTranslatedUserNameAttribute();
    }

    public function getUserTitleAttribute()
    {
        return $this->getTranslatedUserTitleAttribute();
    }
}
