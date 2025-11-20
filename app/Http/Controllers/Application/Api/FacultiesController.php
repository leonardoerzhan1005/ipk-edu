<?php

namespace App\Http\Controllers\Application\Api;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationFaculty;

class FacultiesController extends Controller
{
    public function index()
    {
        $items = ApplicationFaculty::with('translations')->get()
            ->map(fn($f) => ['id' => $f->id, 'name' => $f->name]);
        
        return response()->json($items);
    }
}
