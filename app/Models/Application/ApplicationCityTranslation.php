<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationCityTranslation extends Model
{
    use HasFactory;

    protected $table = 'application_city_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'city_id',
        'locale', 
        'name'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(ApplicationCity::class, 'city_id');
    }
}
