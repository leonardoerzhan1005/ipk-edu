<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IssuedCertificate;
use App\Models\User;
use App\Models\Course;
use App\Service\CertificateService;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $students = User::query()
            ->where('role', 'student')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'q'));
    }

    public function show($locale, $user)
    {
        $student = User::where('role', 'student')->findOrFail($user);

        $certificates = IssuedCertificate::with('course')
            ->where('user_id', $student->id)
            ->latest('issued_at')
            ->paginate(10, ['*'], 'certificates');

        return view('admin.students.show', compact('student', 'certificates'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        $data['role'] = 'student';
        $student = User::create($data);
        return redirect()->route('admin.students.show', ['locale' => app()->getLocale(), 'user' => $student->id])
            ->with('success', 'Student created');
    }

    public function edit($locale, $user)
    {
        $student = User::where('role', 'student')->findOrFail($user);
        return view('admin.students.edit', compact('student'));
    }

    public function update($locale, $user, Request $request)
    {
        $student = User::where('role', 'student')->findOrFail($user);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$student->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $student->update($data);
        return redirect()->route('admin.students.show', ['locale' => $locale, 'user' => $student->id])
            ->with('success', 'Student updated');
    }

    public function destroy($locale, $user)
    {
        $student = User::where('role', 'student')->findOrFail($user);
        $student->delete();
        return redirect()->route('admin.students.index', ['locale' => $locale])
            ->with('success', 'Student deleted');
    }

    public function issueCertificate($locale, $user, Request $request, CertificateService $service)
    {
        $student = User::where('role', 'student')->findOrFail($user);
        $data = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'force' => ['sometimes', 'boolean'],
        ]);
        $course = Course::findOrFail($data['course_id']);
        $issued = $service->issueCertificate($student, $course, (bool)($data['force'] ?? false));

        return redirect()
            ->route('admin.students.show', ['locale' => $locale, 'user' => $student->id])
            ->with('success', 'Certificate issued: '.$issued->code);
    }
}


