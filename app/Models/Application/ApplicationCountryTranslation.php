<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationCountryTranslation extends Model
{
    use HasFactory;

    protected $table = 'application_country_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'country_id',
        'locale', 
        'name'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(ApplicationCountry::class, 'country_id');
    }
}
