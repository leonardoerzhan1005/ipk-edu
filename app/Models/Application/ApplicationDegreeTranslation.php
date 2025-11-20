<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDegreeTranslation extends Model
{
    use HasFactory;

    protected $table = 'application_degree_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'degree_id',
        'locale', 
        'name'
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(ApplicationDegree::class, 'degree_id');
    }
}
