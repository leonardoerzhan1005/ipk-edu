<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationSpecialty extends Model
{
    use HasFactory;

    protected $table = 'application_specialties';
    
    protected $fillable = ['faculty_id', 'slug'];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(ApplicationFaculty::class, 'faculty_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(\App\Models\Course::class, 'specialty_id');
    }

    public function translations()
    {
        return $this->hasMany(ApplicationSpecialtyTranslation::class, 'specialty_id');
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
