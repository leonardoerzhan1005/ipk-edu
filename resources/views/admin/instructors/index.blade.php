@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Instructors</h3>
        <div class="card-actions d-flex gap-2">
          <form method="GET" class="d-flex gap-2">
            <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Search name or email">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search"></i></button>
          </form>
          <a href="{{ route('admin.instructors.create', ['locale' => app()->getLocale()]) }}" class="btn btn-success">Create</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead><tr><th>User</th><th>Email</th><th>Blocked</th><th></th></tr></thead>
            <tbody>
              @forelse($instructors as $p)
              <tr>
                <td>{{ $p->user?->name }}</td>
                <td>{{ $p->user?->email }}</td>
                <td>{{ $p->is_blocked ? 'Yes' : 'No' }}</td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.instructors.edit', ['locale' => app()->getLocale(), 'instructor' => $p->id]) }}">Edit</a>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-secondary">No data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">{{ $instructors->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


