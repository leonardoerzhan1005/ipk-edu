<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstructorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'social_links',
        'is_blocked',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_blocked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(InstructorProfileTranslation::class);
    }

    public function getTranslated(string $field): ?string
    {
        $locale = app()->getLocale();
        $t = $this->translations->firstWhere('locale', $locale);
        return $t?->{$field};
    }
}


