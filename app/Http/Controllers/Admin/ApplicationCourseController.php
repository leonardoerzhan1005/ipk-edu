<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Application\ApplicationFaculty;
use App\Models\Application\ApplicationSpecialty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicationCourseController extends Controller
{
    public function index() : View
    {
        $courses = Course::with(['faculty.translations', 'specialty.translations'])
            ->orderByDesc('id')
            ->paginate(20);
        return view('admin.application.courses.index', compact('courses'));
    }

    public function edit(string $locale, int $courseId) : View
    {
        $course = Course::with(['faculty', 'specialty'])->findOrFail($courseId);
        $faculties = ApplicationFaculty::with('translations')->get();
        $specialties = $course->faculty_id
            ? ApplicationSpecialty::where('faculty_id', $course->faculty_id)->with('translations')->get()
            : collect();
        return view('admin.application.courses.edit', compact('course', 'faculties', 'specialties'));
    }

    public function update(Request $request, string $locale, int $courseId) : RedirectResponse
    {
        $request->validate([
            'is_available_for_applications' => ['nullable', 'boolean'],
            'faculty_id' => ['nullable', 'integer', 'exists:application_faculties,id'],
            'specialty_id' => ['nullable', 'integer', 'exists:application_specialties,id'],
        ]);

        $course = Course::findOrFail($courseId);
        $course->is_available_for_applications = (bool) $request->is_available_for_applications;
        $course->faculty_id = $request->input('faculty_id') ?: null;
        $course->specialty_id = $request->input('specialty_id') ?: null;
        $course->save();

        notyf()->success('Updated Successfully!');
        return to_route('admin.application-courses.index', ['locale' => $locale]);
    }
}


