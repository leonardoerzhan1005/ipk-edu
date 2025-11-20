<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'status'
    ];

    // Связь с переводами
    public function translations(): HasMany
    {
        return $this->hasMany(BlogCategoryTranslation::class, 'blog_category_id');
    }

    // Геттеры для переводов
    public function getTranslatedNameAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->name ?? $this->translations()->where('locale', 'ru')->first()?->name ?? $this->attributes['name'] ?? null;
    }

    public function getTranslatedSlugAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->slug ?? $this->translations()->where('locale', 'ru')->first()?->slug ?? $this->attributes['slug'] ?? null;
    }

    // Обратная совместимость - если нет переводов, используем старые поля
    public function getNameAttribute($value)
    {
        if ($this->translations()->exists()) {
            return $this->getTranslatedNameAttribute();
        }
        return $value;
    }

    public function getSlugAttribute($value)
    {
        if ($this->translations()->exists()) {
            return $this->getTranslatedSlugAttribute();
        }
        return $value;
    }

    function blogs() : HasMany
    {
        return $this->hasMany(Blog::class, 'blog_category_id', 'id');    
    }
}
