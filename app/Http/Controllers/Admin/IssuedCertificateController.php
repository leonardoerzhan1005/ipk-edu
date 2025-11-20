<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IssuedCertificate;
use App\Models\Course;
use App\Models\User;
use App\Service\CertificateService;
use Illuminate\Http\Request;

class IssuedCertificateController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $certificates = IssuedCertificate::with(['user', 'course'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('code', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($sub) use ($q) {
                        $sub->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('course', function ($sub) use ($q) {
                        $sub->where('title', 'like', "%{$q}%");
                    });
            })
            ->latest('issued_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.issued-certificates.index', compact('certificates', 'q'));
    }

    public function show($locale, $issued)
    {
        $issued = IssuedCertificate::with(['user', 'course'])->findOrFail($issued);
        return view('admin.issued-certificates.show', compact('issued'));
    }

    public function store(Request $request, CertificateService $service)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'force' => ['sometimes', 'boolean'],
        ]);

        $user = User::findOrFail($data['user_id']);
        $course = Course::findOrFail($data['course_id']);

        $issued = $service->issueCertificate($user, $course, (bool)($data['force'] ?? false));

        return redirect()
            ->route('admin.issued-certificates.show', ['locale' => app()->getLocale(), 'issued' => $issued->id])
            ->with('success', 'Certificate issued successfully.');
    }

    public function generate($locale, $issued, CertificateService $service)
    {
        $issued = IssuedCertificate::with(['user', 'course'])->findOrFail($issued);
        $result = $service->issueCertificate($issued->user, $issued->course, true);

        return redirect()
            ->route('admin.issued-certificates.show', ['locale' => $locale, 'issued' => $result->id])
            ->with('success', 'Certificate files generated.');
    }
}


