<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    
    protected $fillable = [
        'last_name_ru',
        'first_name_ru',
        'middle_name_ru',
        'last_name_kk',
        'first_name_kk',
        'middle_name_kk',
        'last_name_en',
        'first_name_en',
        'middle_name_en',
        'is_foreign',
        'faculty_id',
        'specialty_id',
        'course_id',
        'custom_course_name',
        'course_language_id',
        'workplace',
        'org_type_id',
        'is_unemployed',
        'country_id',
        'city_id',
        'address_line',
        'degree_id',
        'position',
        'subjects',
        'email',
        'phone',
        'user_id',
        'status',
    ];

    protected $casts = [
        'is_foreign' => 'boolean',
        'is_unemployed' => 'boolean',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(ApplicationFaculty::class, 'faculty_id');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(ApplicationSpecialty::class, 'specialty_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Course::class, 'course_id');
    }

    public function courseLanguage(): BelongsTo
    {
        return $this->belongsTo(ApplicationCourseLanguage::class, 'course_language_id');
    }

    public function orgType(): BelongsTo
    {
        return $this->belongsTo(ApplicationOrgType::class, 'org_type_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(ApplicationCountry::class, 'country_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(ApplicationCity::class, 'city_id');
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(ApplicationDegree::class, 'degree_id');
    }

    // Связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Геттер для полного имени на русском
    public function getFullNameRuAttribute(): string
    {
        $parts = array_filter([$this->last_name_ru, $this->first_name_ru, $this->middle_name_ru]);
        return implode(' ', $parts);
    }

    // Геттер для полного имени на казахском
    public function getFullNameKkAttribute(): ?string
    {
        $parts = array_filter([$this->last_name_kk, $this->first_name_kk, $this->middle_name_kk]);
        return implode(' ', $parts);
    }

    // Геттер для полного имени на английском
    public function getFullNameEnAttribute(): ?string
    {
        if (!$this->is_foreign) {
            return null;
        }
        $parts = array_filter([$this->last_name_en, $this->first_name_en, $this->middle_name_en]);
        return implode(' ', $parts);
    }

    // Геттер для имени на текущем языке
    public function getFullNameAttribute(): string
    {
        $locale = app()->getLocale();
        return match($locale) {
            'ru' => $this->full_name_ru,
            'kk' => $this->full_name_kk ?? $this->full_name_ru,
            'en' => $this->full_name_en ?? $this->full_name_ru,
            default => $this->full_name_ru,
        };
    }

    // Геттер для названия курса (из списка или кастомного)
    public function getCourseNameAttribute(): ?string
    {
        if ($this->course_selection_type === 'custom') {
            return $this->custom_course_name;
        }
        
        return $this->course ? $this->course->translated_title ?? $this->course->title : null;
    }
}
