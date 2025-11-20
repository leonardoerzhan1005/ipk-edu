<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationFacultyTranslation extends Model
{
    use HasFactory;

    protected $table = 'application_faculty_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'faculty_id',
        'locale', 
        'name'
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(ApplicationFaculty::class, 'faculty_id');
    }
}
