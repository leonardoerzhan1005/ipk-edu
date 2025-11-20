<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationOrgType extends Model
{
    use HasFactory;

    protected $table = 'application_org_types';
    
    protected $fillable = ['slug'];

    public function translations()
    {
        return $this->hasMany(ApplicationOrgTypeTranslation::class, 'org_type_id');
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
