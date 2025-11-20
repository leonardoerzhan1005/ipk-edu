<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCourseLanguage extends Model
{
    use HasFactory;

    protected $table = 'application_course_languages';
    
    protected $fillable = ['code']; // 'ru', 'kk', 'en'

    public function translations()
    {
        return $this->hasMany(\App\Models\Application\ApplicationCourseLanguageTranslation::class, 'course_language_id');
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
