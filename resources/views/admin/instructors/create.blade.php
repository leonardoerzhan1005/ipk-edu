@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <form class="card" method="POST" action="{{ route('admin.instructors.store', ['locale' => app()->getLocale()]) }}">
      @csrf
      <div class="card-header"><h3 class="card-title">Create Instructor</h3></div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">User</label>
            <select class="form-select" name="user_id" required>
              <option value="">Select user</option>
              @foreach($users as $u)
              <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Avatar path</label>
            <input type="text" class="form-control" name="avatar" placeholder="/uploads/...">
          </div>
          <div class="col-md-12">
            <label class="form-label">Title ({{ app()->getLocale() }})</label>
            <input type="text" class="form-control" name="title" placeholder="Title">
          </div>
          <div class="col-md-6">
            <label class="form-label">Position ({{ app()->getLocale() }})</label>
            <input type="text" class="form-control" name="position" placeholder="Position">
          </div>
          <div class="col-md-6">
            <label class="form-label">Short bio ({{ app()->getLocale() }})</label>
            <input type="text" class="form-control" name="short_bio" placeholder="Short bio">
          </div>
          <div class="col-md-12">
            <label class="form-label">Bio ({{ app()->getLocale() }})</label>
            <textarea class="form-control" name="bio" rows="4"></textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label">Achievements ({{ app()->getLocale() }})</label>
            <textarea class="form-control" name="achievements" rows="3"></textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label">Highlights ({{ app()->getLocale() }})</label>
            <textarea class="form-control" name="highlights" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('admin.instructors.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary" type="submit">Create</button>
      </div>
    </form>
  </div>
</div>
@endsection


