<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationCity extends Model
{
    use HasFactory;

    protected $table = 'application_cities';
    
    protected $fillable = ['country_id', 'slug'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(ApplicationCountry::class, 'country_id');
    }

    public function translations()
    {
        return $this->hasMany(ApplicationCityTranslation::class, 'city_id');
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
