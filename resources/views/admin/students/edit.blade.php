@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <form class="card" method="POST" action="{{ route('admin.students.update', ['locale' => app()->getLocale(), 'user' => $student->id]) }}">
      @csrf
      @method('PUT')
      <div class="card-header"><h3 class="card-title">Edit Student</h3></div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $student->name) }}" required>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $student->email) }}" required>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Password (leave blank to keep)</label>
            <input type="password" class="form-control" name="password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('admin.students.show', ['locale' => app()->getLocale(), 'user' => $student->id]) }}" class="btn btn-outline-secondary">Back</a>
        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">Save</button>
          <form method="POST" action="{{ route('admin.students.destroy', ['locale' => app()->getLocale(), 'user' => $student->id]) }}" onsubmit="return confirm('Delete this student?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection


