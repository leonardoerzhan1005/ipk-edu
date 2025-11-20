<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\IssuedCertificate;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q'));

        $result = null;
        if ($query !== '') {
            $result = IssuedCertificate::with(['user','course'])
                ->where('code', $query)
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->latest('issued_at')
                ->first();
        }

        return view('frontend.pages.certificates', compact('result', 'query'));
    }
}


