@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <form class="card" method="POST" action="{{ route('admin.instructors.update', ['locale' => app()->getLocale(), 'instructor' => $profile->id]) }}">
      @csrf
      @method('PUT')
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit Instructor</h3>
        <a href="{{ route('admin.instructors.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">Back</a>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">User</label>
            <input class="form-control" value="{{ $profile->user?->name }} ({{ $profile->user?->email }})" disabled>
          </div>
          <div class="col-md-4">
            <label class="form-label">Avatar path</label>
            <input type="text" class="form-control" name="avatar" value="{{ $profile->avatar }}">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_blocked" value="1" {{ $profile->is_blocked ? 'checked' : '' }}>
              <label class="form-check-label">Blocked</label>
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label">Title ({{ app()->getLocale() }})</label>
            <input type="text" class="form-control" name="title" value="{{ $profile->getTranslated('title') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Position ({{ app()->getLocale() }})</label>
            <input type="text" class="form-control" name="position" value="{{ $profile->getTranslated('position') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Short bio ({{ app()->getLocale() }})</label>
            <input type="text" class="form-control" name="short_bio" value="{{ $profile->getTranslated('short_bio') }}">
          </div>
          <div class="col-md-12">
            <label class="form-label">Bio ({{ app()->getLocale() }})</label>
            <textarea class="form-control" name="bio" rows="4">{{ $profile->getTranslated('bio') }}</textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label">Achievements ({{ app()->getLocale() }})</label>
            <textarea class="form-control" name="achievements" rows="3">{{ $profile->getTranslated('achievements') }}</textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label">Highlights ({{ app()->getLocale() }})</label>
            <textarea class="form-control" name="highlights" rows="3">{{ $profile->getTranslated('highlights') }}</textarea>
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


