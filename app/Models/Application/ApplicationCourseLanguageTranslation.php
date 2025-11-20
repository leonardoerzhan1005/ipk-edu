<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCourseLanguageTranslation extends Model
{
    use HasFactory;

    protected $table = 'app_course_lang_trans';
    
    protected $fillable = ['course_language_id', 'locale', 'name'];
}
