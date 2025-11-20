@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Students</h3>
        <div class="card-actions d-flex gap-2">
          <form method="GET" class="d-flex gap-2">
            <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Search name or email">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search"></i> Search</button>
          </form>
          <a class="btn btn-success" href="{{ route('admin.students.create', ['locale' => app()->getLocale()]) }}"><i class="ti ti-user-plus"></i> Create</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($students as $student)
              <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td class="text-end d-flex gap-2 justify-content-end">
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.students.edit', ['locale' => app()->getLocale(), 'user' => $student->id]) }}">Edit</a>
                  <a class="btn btn-sm btn-primary" href="{{ route('admin.students.show', ['locale' => app()->getLocale(), 'user' => $student->id]) }}">Open</a>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-secondary">No students</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">{{ $students->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


