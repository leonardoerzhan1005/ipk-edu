<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationOrgTypeTranslation extends Model
{
    use HasFactory;

    protected $table = 'application_org_type_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'org_type_id',
        'locale', 
        'name'
    ];

    public function orgType(): BelongsTo
    {
        return $this->belongsTo(ApplicationOrgType::class, 'org_type_id');
    }
}
