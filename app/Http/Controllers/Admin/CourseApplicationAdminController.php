<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseApplication;
use Illuminate\Http\Request;

class CourseApplicationAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $status = $request->get('status');
        $apps = CourseApplication::with(['course','session'])
            ->when($q !== '', function($qr) use ($q){
                $qr->where('full_name','like',"%{$q}%")
                   ->orWhere('email','like',"%{$q}%");
            })
            ->when($status, fn($qr)=>$qr->where('status',$status))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        return view('admin.course-applications.index', compact('apps','q','status'));
    }

    public function updateStatus(Request $request, string $locale, CourseApplication $application)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $application->status = $request->status;
        $application->approved_at = $request->status === 'approved' ? now() : null;
        $application->save();
        return back()->with('success', __('Status updated'));
    }

    public function show(string $locale, CourseApplication $application)
    {
        $application->load(['course','session','user']);
        $related = \App\Models\Application\Application::query()
            ->where('email', $application->email)
            ->when($application->course_id, fn($q)=>$q->where('course_id', $application->course_id))
            ->latest()
            ->first();
        return view('admin.course-applications.show', compact('application','related'));
    }
}


