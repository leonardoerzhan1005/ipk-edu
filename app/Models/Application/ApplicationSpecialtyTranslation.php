<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationSpecialtyTranslation extends Model
{
    use HasFactory;

    protected $table = 'application_specialty_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'specialty_id',
        'locale', 
        'name'
    ];

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(ApplicationSpecialty::class, 'specialty_id');
    }
}
