@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <form class="card" method="POST" action="{{ route('admin.students.store', ['locale' => app()->getLocale()]) }}">
      @csrf
      <div class="card-header"><h3 class="card-title">Create Student</h3></div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('admin.students.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary" type="submit">Create</button>
      </div>
    </form>
  </div>
</div>
@endsection


