@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Application Settings: {{ $course->title }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.application-courses.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Back 
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.application-courses.update', ['locale' => app()->getLocale(), 'course' => $course->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Available for Applications</label>
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_available_for_applications" value="1" {{ $course->is_available_for_applications ? 'checked' : '' }}>
                                <span class="form-check-label">Toggle</span>
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Faculty (optional)</label>
                            <select name="faculty_id" id="faculty_id" class="form-control">
                                <option value="">— Not linked —</option>
                                @foreach($faculties as $f)
                                    <option value="{{ $f->id }}" {{ $course->faculty_id == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Specialization (optional)</label>
                            <select name="specialty_id" id="specialty_id" class="form-control">
                                <option value="">— Not linked —</option>
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}" {{ $course->specialty_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('specialty_id')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


