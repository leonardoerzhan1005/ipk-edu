@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manage Content: {{ $course->title }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.course-contents-v2.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex gap-2">
                        <a class="btn btn-primary dynamic-modal-btn" data-id="{{ $course->id }}" data-url="{{ route('admin.course-content.create-chapter', ['locale' => app()->getLocale(), 'course' => $course->id]) }}">Add Chapter</a>
                        <a class="btn btn-secondary sort_chapter_btn" data-id="{{ $course->id }}" href="javascript:;">Sort Chapters</a>
                    </div>

                    <div class="accordion" id="accordionExample">
                        @foreach ($chapters as $chapter)
                            <div class="accordion-item">
                                <h2 class="accordion-header d-flex align-items-center">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $chapter->id }}" aria-expanded="true" aria-controls="collapse-{{ $chapter->id }}">
                                        <span>{{ $chapter->title }}</span>
                                    </button>
                                    <div class="ms-auto add_course_content_action_btn">
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="add_lesson" data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $chapter->course_id }}">
                                                    <a class="dropdown-item" href="javascript:;">Add Lesson</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <a class="edit edit_chapter" data-course-id="{{ $chapter->course_id }}" data-chapter-id="{{ $chapter->id }}" href="#"><i class="ti ti-edit"></i></a>
                                        <a class="del delete-item" href="{{ route('admin.course-content.destory-chapter', ['locale' => app()->getLocale(), 'chapter' => $chapter->id]) }}"><i class="ti ti-trash-x"></i></a>
                                    </div>
                                </h2>
                                <div id="collapse-{{ $chapter->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="item_list sortable_list">
                                            @foreach($chapter->lessons ?? [] as $lesson)
                                                <li class="" data-lesson-id="{{ $lesson->id }}" data-chapter-id="{{ $chapter->id }}">
                                                    <span>{{ $lesson->title }}</span>
                                                    <div class="add_course_content_action_btn">
                                                        <a class="edit_lesson" data-lesson-id="{{ $lesson->id }}" data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $chapter->course_id }}" class="edit" href="javascript:;"><i class="ti ti-edit"></i></a>
                                                        <a class="del delete-item" href="{{ route('admin.course-content.destroy-lesson', ['locale' => app()->getLocale(), 'id' => $lesson->id]) }}"><i class="ti ti-trash-x"></i></a>
                                                        <a class="arrow dragger" href="javascript:;"><i class="ti ti-arrows-maximize"></i></a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/admin/course.js')
@endpush


