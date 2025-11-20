<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationCountry extends Model
{
    use HasFactory;

    protected $table = 'application_countries';
    
    protected $fillable = ['code'];

    public function cities(): HasMany
    {
        return $this->hasMany(ApplicationCity::class, 'country_id');
    }

    public function translations()
    {
        return $this->hasMany(ApplicationCountryTranslation::class, 'country_id');
    }

    // Унифицированный доступ к name с учётом текущего locale
    public function getNameAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        $tr = $translations->firstWhere('locale', $locale)
           ?? $translations->firstWhere('locale', 'ru')
           ?? $translations->first();

        return $tr?->name;
    }
}
