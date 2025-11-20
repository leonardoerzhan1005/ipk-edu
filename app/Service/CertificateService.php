<?php

namespace App\Service;

use App\Models\CertificateBuilder;
use App\Models\CertificateBuilderItem;
use App\Models\Course;
use App\Models\IssuedCertificate;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class CertificateService
{
    public function issueCertificate(User $user, Course $course, bool $forceCompletion = false): IssuedCertificate
    {
        \Log::info('CertificateService.issueCertificate start', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'force' => $forceCompletion,
        ]);

        if (!$forceCompletion) {
            $watchedCount = \App\Models\WatchHistory::where([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'is_completed' => 1,
            ])->count();
            $totalLessons = $course->lessons()->count();
            if ($watchedCount !== $totalLessons) {
                abort(403, 'Course not completed.');
            }
        }

        $issued = IssuedCertificate::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            $this->generateIssueAttributes()
        );

        // Persist issued locale once, so future regenerations stay in original language
        if (!$issued->issued_locale) {
            $issued->issued_locale = app()->getLocale();
            $issued->save();
        }

        // Prepare QR before rendering HTML so the file exists when DomPDF reads it
        $qrRelative = 'certificates/' . $issued->code . '.qr.png';
        try {
            $qr = app(QrCodeGenerator::class);
            $qrPng = $qr->format('png')->size(140)->margin(1)->generate(url('/c/'.$issued->code));
            $okQr = Storage::disk('public')->put($qrRelative, $qrPng);
            \Log::info('CertificateService.writeQr', ['path' => $qrRelative, 'ok' => $okQr]);
        } catch (\Throwable $e) {
            \Log::warning('CertificateService.qrFailed', ['error' => $e->getMessage()]);
        }

        $qrAbsolutePath = public_path($qrRelative);
        // Render using original issued locale
        $previous = app()->getLocale();
        if (!empty($issued->issued_locale)) {
            app()->setLocale($issued->issued_locale);
        }
        $html = $this->renderCertificateHtml($user, $course, $issued, $qrAbsolutePath);
        // restore
        app()->setLocale($previous);

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);
        $pdfContent = $pdf->output();

        // Our 'public' disk points to public_path('/'), so save under 'certificates/...'
        $pdfRelative = 'certificates/' . $issued->code . '.pdf';
        $ok = Storage::disk('public')->put($pdfRelative, $pdfContent);
        \Log::info('CertificateService.writePdf', ['path' => $pdfRelative, 'ok' => $ok]);
        if ($ok) {
            // Build URL-relative path for href/src
            $issued->file_path = $pdfRelative;
        }

        if (class_exists(\Imagick::class)) {
            try {
                $imagick = new \Imagick();
                $imagick->setResolution(200, 200);
                $imagick->readImageBlob($pdfContent);
                $imagick->setIteratorIndex(0);
                $imagick->setImageFormat('png');
                $pngContent = $imagick->getImageBlob();
                $pngRelative = 'certificates/' . $issued->code . '.png';
                $okPng = Storage::disk('public')->put($pngRelative, $pngContent);
                if ($okPng) {
                    $issued->png_path = $pngRelative;
                }
                \Log::info('CertificateService.writePng', ['path' => $pngRelative, 'ok' => $okPng]);
                $imagick->clear();
                $imagick->destroy();
            } catch (\Throwable $e) {
                \Log::warning('CertificateService.pngFailed', ['error' => $e->getMessage()]);
            }
        }

        $issued->issued_at = $issued->issued_at ?: now();
        $issued->save();
        \Log::info('CertificateService.issueCertificate done', [
            'id' => $issued->id,
            'file_path' => $issued->file_path,
            'png_path' => $issued->png_path,
        ]);

        return $issued;
    }

    public function renderCertificateHtml(User $user, Course $course, IssuedCertificate $issued, ?string $qrAbsolutePath = null): string
    {
        $certificate = CertificateBuilder::first();
        $certificateItems = CertificateBuilderItem::all();
        $html = view('frontend.student-dashboard.enrolled-course.certificate', compact('certificate', 'certificateItems'))
            ->render();

        $replacements = [
            '[student_name]' => $user->name,
            '[student_full_name]' => $user->name,
            '[course_name]' => $course->translated_title ?? $course->title,
            '[course_title]' => $course->translated_title ?? $course->title,
            '[date]' => date('d-m-Y'),
            '[platform_name]' => config('app.name', 'Edu Core'),
            '[instructor_name]' => $course->instructor->name ?? '',
            '[certificate_code]' => $issued->code,
            '[reference_number]' => str_pad((string)$issued->id, 4, '0', STR_PAD_LEFT),
            '[certificate_url]' => url('/'.app()->getLocale().'/certificates?q='.$issued->code),
            '[hours]' => (string)($course->duration ?? ''),
            '[platform_logo]' => public_path(config('settings.site_logo')),
            '[qr_src]' => $qrAbsolutePath ?: public_path('certificates/'.$issued->code.'.qr.png'),
        ];

        return strtr($html, $replacements);
    }

    protected function generateIssueAttributes(): array
    {
        return DB::transaction(function () {
            // UC-style code like UC-e5e18d0d-6abe-4450-9b10-d7e4decfbeae
            $code = 'UC-' . Str::uuid()->toString();
            return [
                'code' => $code,
                'issued_at' => now(),
            ];
        });
    }
}


