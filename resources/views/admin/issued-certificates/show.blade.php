@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title mb-1">Certificate {{ $issued->code }}</div>
          <div class="text-secondary">Issued {{ optional($issued->issued_at)->format('Y-m-d H:i') }}</div>
        </div>
        <div class="card-actions">
          @if($issued->file_path)
            <a href="/{{ $issued->file_path }}" class="btn btn-outline-primary" target="_blank"><i class="ti ti-file-type-pdf"></i> PDF</a>
          @endif
          @if(!empty($issued->png_path))
            <a href="/{{ $issued->png_path }}" class="btn btn-outline-secondary" target="_blank"><i class="ti ti-photo"></i> PNG</a>
          @endif
          <form method="POST" action="{{ route('admin.issued-certificates.generate', ['locale' => app()->getLocale(), 'issued' => $issued->id]) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Generate files</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-2">User</div>
              <div>{{ $issued->user?->name }}</div>
              <div class="text-secondary">ID: {{ $issued->user?->id }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-2">Course</div>
              <div class="mb-2">{{ $issued->course?->title }}</div>
              <a href="{{ route('admin.courses.edit', ['locale' => app()->getLocale(), 'id' => $issued->course?->id]) }}" class="btn btn-sm btn-outline-primary">Open course</a>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <div class="fw-semibold mb-2">Certificate preview</div>
          @if(!empty($issued->png_path))
            <div class="border rounded p-2 bg-light text-center">
              <img src="/{{ $issued->png_path }}" alt="Certificate Preview" class="img-fluid" style="max-width:100%; height:auto;">
            </div>
          @elseif(!empty($issued->file_path))
            <div class="border rounded" style="height:720px;">
              <iframe src="/{{ $issued->file_path }}" width="100%" height="100%" style="border:0;" title="Certificate PDF"></iframe>
            </div>
          @else
            <div class="text-secondary">No file generated yet. Use "Issue Certificate" to generate PDF (and PNG if Imagick is enabled).</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


