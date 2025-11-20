@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>
          <div class="card-title mb-1">Application #{{ $application->id }}</div>
          <div class="text-secondary">{{ $application->created_at->format('Y-m-d H:i') }}</div>
        </div>
        <div class="card-actions">
          <form method="POST" action="{{ route('admin.course-applications.status', ['locale'=>app()->getLocale(), 'application'=>$application->id]) }}" class="d-inline">
            @csrf
            <input type="hidden" name="status" value="approved">
            <button class="btn btn-success" @disabled($application->status==='approved')>Approve</button>
          </form>
          <form method="POST" action="{{ route('admin.course-applications.status', ['locale'=>app()->getLocale(), 'application'=>$application->id]) }}" class="d-inline">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <button class="btn btn-danger" @disabled($application->status==='rejected')>Reject</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-2">Applicant</div>
              <div>{{ $application->full_name }}</div>
              <div class="text-secondary">Email: {{ $application->email }}</div>
              <div class="text-secondary">Phone: {{ $application->phone }}</div>
              @if($related)
                <hr>
                <div class="fw-semibold mb-2">Profile (from full form)</div>
                <div class="text-secondary">Country: {{ optional($related->country)->name ?? '—' }}</div>
                <div class="text-secondary">City: {{ optional($related->city)->name ?? '—' }}</div>
                <div class="text-secondary">Address: {{ $related->address_line ?? '—' }}</div>
                <div class="text-secondary">Degree: {{ optional($related->degree)->name ?? '—' }}</div>
                <div class="text-secondary">Position: {{ $related->position ?? '—' }}</div>
                <div class="text-secondary">Subjects: {{ $related->subjects ?? '—' }}</div>
              @endif
              <div class="mt-2"><span class="badge @class(['bg-yellow'=> $application->status==='pending','bg-lime'=> $application->status==='approved','bg-red'=> $application->status==='rejected'])">{{ ucfirst($application->status) }}</span></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-2">Course</div>
              <div class="mb-2">{{ $application->course?->title }}</div>
              @if($application->session)
                <div class="text-secondary">{{ $application->session->start_date->format('Y-m-d') }} — {{ $application->session->end_date->format('Y-m-d') }}</div>
              @endif
              <div class="text-secondary">Course language: {{ $application->course_language_id ?? '—' }}</div>
            </div>
          </div>
          @if($application->message)
          <div class="col-12">
            <div class="border rounded p-3">
              <div class="fw-semibold mb-2">Message</div>
              <div class="text-secondary">{{ $application->message }}</div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


