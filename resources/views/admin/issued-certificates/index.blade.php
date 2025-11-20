@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Issued Certificates</h3>
        <div class="card-actions">
          <form method="GET" class="d-flex gap-2">
            <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Search code, user or course">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search"></i> Search</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <form class="row g-2 mb-3" method="POST" action="{{ route('admin.issued-certificates.store', ['locale' => app()->getLocale()]) }}">
          @csrf
          <div class="col-md-3">
            <input type="number" name="user_id" class="form-control" placeholder="User ID" required>
          </div>
          <div class="col-md-3">
            <input type="number" name="course_id" class="form-control" placeholder="Course ID" required>
          </div>
          <div class="col-md-2 d-flex align-items-center">
            <label class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="force" value="1">
              <span class="form-check-label">Force (ignore completion)</span>
            </label>
          </div>
          <div class="col-md-2">
            <button class="btn btn-success w-100" type="submit">Issue Certificate</button>
          </div>
        </form>
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>Code</th>
                <th>User</th>
                <th>Course</th>
                <th>Issued at</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @forelse($certificates as $cert)
              <tr>
                <td class="fw-semibold"><a href="{{ route('admin.issued-certificates.show', ['locale' => app()->getLocale(), 'issued' => $cert->id]) }}">{{ $cert->code }}</a></td>
                <td>{{ $cert->user?->name }}</td>
                <td><a href="{{ route('admin.courses.edit', ['locale' => app()->getLocale(), 'id' => $cert->course?->id]) }}">{{ $cert->course?->title }}</a></td>
                <td>{{ optional($cert->issued_at)->format('Y-m-d H:i') }}</td>
                <td>
                  @if($cert->file_path)
                    <a class="btn btn-sm btn-outline-primary" href="/{{ $cert->file_path }}" target="_blank">PDF</a>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center">No data</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $certificates->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


