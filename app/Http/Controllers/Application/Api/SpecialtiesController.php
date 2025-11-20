<?php

namespace App\Http\Controllers\Application\Api;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationSpecialty;
use Illuminate\Http\Request;

class SpecialtiesController extends Controller
{
    public function index(Request $request)
    {
        $facultyId = $request->get('faculty_id');
        
        if (!$facultyId) {
            return response()->json([]);
        }
        
        $specialties = ApplicationSpecialty::where('faculty_id', $facultyId)
            ->with('translations')
            ->get()
            ->map(function ($specialty) {
                return [
                    'id' => $specialty->id,
                    'name' => $specialty->name
                ];
            });
        
        return response()->json($specialties);
    }
}
