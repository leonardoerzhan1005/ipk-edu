<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'category_id',
        'title',
        'slug',
        'seo_description',
        'duration',
        'thumbnail',
        'demo_video_storage',
        'demo_video_source',
        'description',
        'capacity',
        'price',
        'discount',
        'certificate',
        'course_level_id',
        'course_language_id',
        'qna',
        'message_for_reviewer',
        'status',
        'is_approved',
        'faculty_id',
        'specialty_id',
        'is_available_for_applications'
    ];


    function instructor() : HasOne{
        return $this->hasOne(User::class, 'id', 'instructor_id');
    }

    function category() : HasOne
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category_id');
    }

    function level() : HasOne{
        return $this->hasOne(CourseLevel::class, 'id', 'course_level_id');
    }
    function language() : HasOne{
        return $this->hasOne(CourseLanguage::class, 'id', 'course_language_id');
    }

    // Новые связи для анкет
    function faculty() : BelongsTo{
        return $this->belongsTo(\App\Models\Application\ApplicationFaculty::class, 'faculty_id');
    }

    function specialty() : BelongsTo{
        return $this->belongsTo(\App\Models\Application\ApplicationSpecialty::class, 'specialty_id');
    }

    // Связь с переводами
    function translations() : HasMany{
        return $this->hasMany(CourseTranslation::class, 'course_id');
    }

    // Геттеры для переводов
    public function getTranslatedTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->title ?? $this->title;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->description ?? $this->description;
    }

    public function getTranslatedSeoDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->seo_description ?? $this->seo_description;
    }

    public function getTranslatedSlugAttribute(): ?string
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation?->slug ?? $this->slug;
    }


    function chapters() : HasMany
    {
        return $this->hasMany(CourseChapter::class, 'course_id', 'id')->orderBy('order');
    }

    function lessons() : HasMany
    {
        return $this->hasMany(CourseChapterLession::class, 'course_id', 'id');
    }

    function reviews() : HasMany
    {
        return $this->hasMany(Review::class, 'course_id', 'id');
    }

    function enrollments() : HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id', 'id');
    }
}
