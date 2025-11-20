<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDegree extends Model
{
    use HasFactory;

    protected $table = 'application_degrees';
    
    protected $fillable = ['slug'];

    public function translations()
    {
        return $this->hasMany(ApplicationDegreeTranslation::class, 'degree_id');
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
