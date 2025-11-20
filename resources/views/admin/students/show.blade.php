@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title mb-1">Student #{{ $student->id }}</div>
          <div class="text-secondary">{{ $student->name }} — {{ $student->email }}</div>
        </div>
        <div class="card-actions">
          <a href="{{ route('admin.students.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">Back</a>
          <a href="{{ route('admin.students.edit', ['locale' => app()->getLocale(), 'user' => $student->id]) }}" class="btn btn-primary">Edit</a>
        </div>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-2">Issue certificate</div>
              <form method="POST" action="{{ route('admin.students.issue-certificate', ['locale' => app()->getLocale(), 'user' => $student->id]) }}" class="row g-2">
                @csrf
                <div class="col-md-8">
                  <input type="number" name="course_id" class="form-control" placeholder="Course ID" required>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                  <label class="form-check m-0">
                    <input class="form-check-input" type="checkbox" name="force" value="1">
                    <span class="form-check-label">Force</span>
                  </label>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary w-100">Issue</button>
                </div>
              </form>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-2">Certificates</div>
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead><tr><th>Code</th><th>Course</th><th>Issued</th><th></th></tr></thead>
                  <tbody>
                    @forelse($certificates as $c)
                      <tr>
                        <td>{{ $c->code }}</td>
                        <td>{{ $c->course?->title }}</td>
                        <td>{{ optional($c->issued_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.issued-certificates.show', ['locale' => app()->getLocale(), 'issued' => $c->id]) }}">Open</a></td>
                      </tr>
                    @empty
                      <tr><td colspan="4" class="text-secondary">No certificates</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div class="mt-2">{{ $certificates->links() }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


