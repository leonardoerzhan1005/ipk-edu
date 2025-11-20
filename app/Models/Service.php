<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon_class',
        'image',
        'button_label',
        'button_link',
        'status',
        'display_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }

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

    public function getTranslatedLeftItemsAttribute(): array
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->left_items ?? [];
    }

    public function getTranslatedRightItemsAttribute(): array
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        $t = $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', 'ru')
            ?? $translations->first();
        return $t?->right_items ?? [];
    }
}


