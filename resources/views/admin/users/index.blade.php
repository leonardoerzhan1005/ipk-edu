@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Users</h3>
        <div class="card-actions d-flex gap-2">
          <form method="GET" class="d-flex gap-2">
            <select class="form-select" name="role">
              <option value="">All roles</option>
              <option value="student" {{ $role==='student'?'selected':'' }}>Student</option>
              <option value="instructor" {{ $role==='instructor'?'selected':'' }}>Instructor</option>
              <option value="admin" {{ $role==='admin'?'selected':'' }}>Admin</option>
            </select>
            <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Search name or email">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search"></i></button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Blocked</th><th></th></tr></thead>
            <tbody>
              @foreach($users as $u)
              <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role }}</td>
                <td>{{ $u->is_blocked ? 'Yes' : 'No' }}</td>
                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', ['locale' => app()->getLocale(), 'user' => $u->id]) }}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


