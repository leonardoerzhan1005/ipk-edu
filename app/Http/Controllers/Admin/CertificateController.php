<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateBuilder;
use App\Models\CertificateBuilderItem;
use App\Models\IssuedCertificate;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    //
    function download(Course $course) {
        $watchedLessonCount = \App\Models\WatchHistory::where(['user_id' => user()->id, 'course_id' => $course->id, 'is_completed' => 1])->count();
        $lessonCount = $course->lessons()->count();

        if($watchedLessonCount != $lessonCount) return abort(404);

        $certificate = CertificateBuilder::first();
        $certificateItems = CertificateBuilderItem::all();
        $html = view('frontend.student-dashboard.enrolled-course.certificate', compact('certificate', 'certificateItems'))->render();
        
        $html = str_replace("[student_name]", user()->name, $html);
        $html = str_replace("[course_name]", $course->title, $html);
        $html = str_replace("[date]", date('d-m-Y'), $html);
        $html = str_replace("[platform_name]", 'Edu Core', $html);
        $html = str_replace("[instructor_name]", $course->instructor->name, $html);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        // Issue or reuse certificate with unique code
        $issued = IssuedCertificate::where(['user_id' => user()->id, 'course_id' => $course->id])->first();
        if (!$issued) {
            $issued = DB::transaction(function () use ($course) {
                $year = date('Y');
                // naive sequential number within year
                $sequence = (IssuedCertificate::whereYear('issued_at', $year)->max(DB::raw('CAST(SUBSTRING(code, 11) AS UNSIGNED)')) ?? 0) + 1;
                $code = sprintf('CERT-%s-%06d', $year, $sequence);
                return IssuedCertificate::create([
                    'user_id' => user()->id,
                    'course_id' => $course->id,
                    'code' => $code,
                    'issued_at' => now(),
                ]);
            });
        }

        // Save PDF to storage and path to model
        $fileName = $issued->code . '.pdf';
        $relativePath = 'certificates/' . $fileName;
        Storage::disk('public')->put($relativePath, $pdf->output());
        if (!$issued->file_path) {
            $issued->file_path = 'storage/' . $relativePath;
            $issued->save();
        }

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'certificate.pdf');

    }

}
