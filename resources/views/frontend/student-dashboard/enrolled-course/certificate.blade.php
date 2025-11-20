<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificate</title>
    <style>
        .certificate-body {
            width: 930px !important;
            height: 600px !important;
            background: #f3f4f6;
            background-repeat: no-repeat;
            text-align: center;
            position: relative;
            font-family: DejaVu Sans, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
        }

        @page {
            size: 930px 600px;
            margin: 0;
        }

        .certificate-body div {
            /* display: inline-block; */
        }
        .meta { position:absolute; right:40px; top:28px; text-align:right; color:#555; font-size:12px; line-height:1.5; }
        .logo { position:absolute; left:40px; top:28px; height:40px; }
        .heading { position:absolute; left:40px; top:96px; text-align:left; font-size:13px; letter-spacing:2px; color:#6b7280; font-weight:700; font-family: DejaVu Sans, sans-serif; }
        .course-title { position:absolute; left:40px; top:130px; right:40px; text-align:left; font-weight:800; font-size:44px; line-height:1.15; color:#1f2937; font-family: DejaVu Sans, sans-serif; }
        .instructors { position:absolute; left:40px; top:430px; text-align:left; color:#111827; font-size:14px; }
        .label { color:#6b7280; margin-right:8px; }
        .student-name { position:absolute; left:40px; bottom:92px; text-align:left; font-weight:800; font-size:36px; color:#111827; }
        .footer-info { position:absolute; left:40px; bottom:54px; text-align:left; font-size:12px; color:#111827; }
        .footer-info .label { color:#6b7280; }
        .builder-hidden { display:none; }

        @foreach($certificateItems as $item)
            #{{ $item->element_id }} {
                left: {{ $item->x_position }}px;
                top: {{ $item->y_position }}px;
                position: relative;
            }
        @endforeach
    </style>
</head>

<body>
    <div class="certificate-body" style="background-image: url({{ $certificate?->background ? public_path($certificate->background) : '' }});">
        <img class="logo" src="[platform_logo]" alt="logo">
        <div class="meta">
            <div>{{ __('cert.certificate_no') }}: [certificate_code]</div>
            <div>{{ __('cert.certificate_url') }}: [certificate_url]</div>
            <div>{{ __('cert.reference') }}: [reference_number]</div>
        </div>
        <div class="heading">{{ __('cert.heading') }}</div>
        <div class="course-title">[course_title]</div>
        <div class="instructors"><span class="label">{{ __('cert.instructors') }}</span> <b>[instructor_name]</b></div>

        <div id="title" class="title builder-hidden">{{ $certificate->title ?? '' }}</div>
        <div id="subtitle" class="subtitle builder-hidden">{{ $certificate->sub_title ?? '' }}</div>
        <div id="description" class="descrition builder-hidden">{{ $certificate->description ?? '' }}</div>
        <div id="signature" class="signature builder-hidden">
            @if(!empty($certificate?->signature))
                <img src="{{ public_path($certificate->signature) }}" alt="">
            @endif
        </div>
        <div class="student-name">[student_full_name]</div>
        <div class="footer-info">
            <span class="label">{{ __('cert.date') }}</span> <b>[date]</b>
            &nbsp;&nbsp; <span class="label">{{ __('cert.length') }}</span> <b>[hours] {{ __('cert.total_hours_suffix') }}</b>
        </div>
        <div style="position:absolute; left:800px; bottom:20px;">
            <img src="[qr_src]" alt="QR" style="width:80px; height:80px;">
        </div>
    </div>
</body>

</html>
