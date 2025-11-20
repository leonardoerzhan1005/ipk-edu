@extends('frontend.layouts.master')

@section('content')
    <!--===========================
        BREADCRUMB START
    ============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Course Schedule') }}</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li>{{ __('Course Schedule') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
        BREADCRUMB END
    ============================-->

    <!--===========================
        COURSE SCHEDULE PAGE START
    ============================-->
    <section class="wsus__courses mt_120 xs_mt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="wsus__heading_area mb_25">
                        <h2>{{ __('Course Schedule') }}</h2>
                        <p>{{ __('View upcoming course start dates and registration periods') }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter by Category -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('course-schedule.index', ['locale' => app()->getLocale()]) }}"
                           class="btn btn-sm {{ !$categoryId ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ __('All Categories') }}
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('course-schedule.index', ['locale' => app()->getLocale(), 'category' => $category->id]) }}"
                               class="btn btn-sm {{ $categoryId == $category->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $category->translated_name ?? $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if($groupedSessions->count() > 0)
                        @foreach($groupedSessions as $monthKey => $sessions)
                            @php
                                $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthKey);
                                $monthName = $monthDate->translatedFormat('F Y');
                            @endphp

                            <div class="mb-5 wow fadeInUp">
                                <h3 class="mb-4">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    {{ $monthName }}
                                </h3>

                                <div class="row g-4">
                                    @foreach($sessions as $session)
                                        <div class="col-lg-6">
                                            <div class="card shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h5 class="card-title mb-0">
                                                            <a href="{{ route('courses.show', ['locale' => app()->getLocale(), 'slug' => $session->course->translated_slug ?? $session->course->slug]) }}"
                                                               class="text-decoration-none text-dark">
                                                                {{ $session->course->translated_title ?? $session->course->title }}
                                                            </a>
                                                        </h5>
                                                        @if($session->format == 'online')
                                                            <span class="badge bg-info">
                                                                <i class="fas fa-laptop me-1"></i>
                                                                {{ __('Online') }}
                                                            </span>
                                                        @elseif($session->format == 'offline')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-building me-1"></i>
                                                                {{ __('Offline') }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-blender me-1"></i>
                                                                {{ __('Hybrid') }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if($session->course->category)
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-folder me-1"></i>
                                                            {{ $session->course->category->translated_name ?? $session->course->category->name }}
                                                        </p>
                                                    @endif

                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                                            <strong>{{ __('Start Date') }}:</strong>
                                                            <span class="ms-2">{{ $session->start_date->format('d.m.Y') }}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-calendar-times text-danger me-2"></i>
                                                            <strong>{{ __('End Date') }}:</strong>
                                                            <span class="ms-2">{{ $session->end_date->format('d.m.Y') }}</span>
                                                        </div>
                                                    </div>

                                                    @if($session->translated_description)
                                                        <p class="card-text text-muted mb-3">
                                                            {{ $session->translated_description }}
                                                        </p>
                                                    @endif

                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('courses.show', ['locale' => app()->getLocale(), 'slug' => $session->course->translated_slug ?? $session->course->slug]) }}"
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            {{ __('Course Details') }}
                                                        </a>

                                                        @auth
                                                            @if(auth()->user()->role == 'user')
                                                                @php
                                                                    $isEnrolled = auth()->user()->enrollments()->where('course_id', $session->course->id)->exists();
                                                                @endphp

                                                                @if($isEnrolled)
                                                                    <a href="{{ route('enrolled-courses.index', ['locale' => app()->getLocale()]) }}"
                                                                       class="btn btn-success btn-sm">
                                                                        <i class="fas fa-check me-1"></i>
                                                                        {{ __('Enrolled') }}
                                                                    </a>
                                                                @else
                                                                    <button type="button"
                                                                            class="btn btn-warning btn-sm add_to_cart"
                                                                            data-course-id="{{ $session->course->id }}">
                                                                        <i class="fas fa-user-plus me-1"></i>
                                                                        {{ __('Enroll Now') }}
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <a href="{{ route('login', ['locale' => app()->getLocale()]) }}"
                                                               class="btn btn-warning btn-sm">
                                                                <i class="fas fa-sign-in-alt me-1"></i>
                                                                {{ __('Login to Enroll') }}
                                                            </a>
                                                        @endauth

                                                        @if($session->course->is_available_for_applications)
                                                            <a href="{{ route('application.form', ['locale' => app()->getLocale(), 'course_id' => $session->course->id]) }}"
                                                               class="btn btn-info btn-sm">
                                                                <i class="fas fa-file-alt me-1"></i>
                                                                {{ __('Apply') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('No scheduled courses available at the moment. Please check back later.') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--===========================
        COURSE SCHEDULE PAGE END
    ============================-->
@endsection
