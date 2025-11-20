@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <form class="card" method="POST" action="{{ route('admin.users.update', ['locale' => app()->getLocale(), 'user' => $user->id]) }}">
      @csrf
      @method('PUT')
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit User</h3>
        <a href="{{ route('admin.users.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">Back</a>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" required>
              <option value="student" {{ $user->role==='student'?'selected':'' }}>Student</option>
              <option value="instructor" {{ $user->role==='instructor'?'selected':'' }}>Instructor</option>
              <option value="admin" {{ $user->role==='admin'?'selected':'' }}>Admin</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">New Password (optional)</label>
            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_blocked" value="1" {{ $user->is_blocked ? 'checked' : '' }}>
              <label class="form-check-label">Blocked</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-end gap-2">
        <button class="btn btn-primary" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection


