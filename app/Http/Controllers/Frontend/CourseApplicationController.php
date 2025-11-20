<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseApplication;
use App\Models\CourseSession;
use Illuminate\Http\Request;

class CourseApplicationController extends Controller
{
    public function store(Request $request, string $locale, Course $course)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'phone' => ['nullable','string','max:32'],
            'message' => ['nullable','string','max:2000'],
            'course_session_id' => ['nullable','exists:course_sessions,id'],
        ]);

        $data['user_id'] = auth('web')->id();
        $data['course_id'] = $course->id;

        // ensure session belongs to this course
        if (!empty($data['course_session_id'])) {
            $session = CourseSession::where('id', $data['course_session_id'])
                ->where('course_id', $course->id)
                ->firstOrFail();
        }

        // Prevent duplicates: same user (or same email if guest) + same course with pending/approved status
        $email = strtolower(trim($data['email']));
        $existing = CourseApplication::query()
            ->where('course_id', $course->id)
            ->when($data['user_id'], function($q) use ($data){
                $q->where('user_id', $data['user_id']);
            }, function($q) use ($email){
                $q->where('email', $email);
            })
            ->whereIn('status', ['pending','approved'])
            ->first();

        if ($existing) {
            return back()->with('success', __('You have already applied for this course. Our team will contact you soon.'));
        }

        $data['email'] = $email;
        CourseApplication::create($data);

        return back()->with('success', __('Your application has been submitted. Our team will contact you soon.'));
    }
}


