<?php

namespace App\Http\Controllers\Application\Api;

use App\Http\Controllers\Controller;
use App\Models\Application\{
    ApplicationOrgType, ApplicationDegree, ApplicationCourseLanguage
};

class DictionariesController extends Controller
{
    public function orgTypes()
    {
        $items = ApplicationOrgType::with('translations')->get()
            ->map(fn($o) => ['id' => $o->id, 'name' => $o->name, 'slug' => $o->slug]);
        
        return response()->json($items);
    }

    public function degrees()
    {
        $items = ApplicationDegree::with('translations')->get()
            ->map(fn($d) => ['id' => $d->id, 'name' => $d->name, 'slug' => $d->slug]);
        
        return response()->json($items);
    }

    public function courseLanguages()
    {
        $items = ApplicationCourseLanguage::with('translations')->get()
            ->map(fn($l) => ['id' => $l->id, 'code' => $l->code, 'name' => $l->name]);
        
        return response()->json($items);
    }
}
