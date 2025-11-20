<?php

namespace App\Http\Controllers\Application\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $specialtyId = $request->get('specialty_id');
        $facultyId = $request->get('faculty_id');

        $query = Course::query()
            ->where('is_available_for_applications', true)
            ->where('is_approved', 'approved')
            ->where('status', 'active')
            ->with('translations')
            ;

        if ($specialtyId) {
            $query->where('specialty_id', $specialtyId);
        } elseif ($facultyId) {
            $query->where('faculty_id', $facultyId);
        }

        $courses = $query->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->translated_title ?? $course->title
                ];
            });
        
        return response()->json($courses);
    }
}
