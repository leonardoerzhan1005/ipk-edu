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
                            <h1>{{ __('Our Courses') }}</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li>{{ __('Our Courses') }}</li>
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
        COURSES PAGE START
    ============================-->
    <section class="wsus__courses mt_120 xs_mt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-8 order-2 order-lg-1 wow fadeInLeft">
                    <div class="wsus__sidebar">
                        <form action="{{ localizedRoute('courses.index') }}">
                            <div class="wsus__sidebar_search">
                                <input type="text" placeholder="{{ __('Search Course') }}" name="search" value="{{ request()->search ?? '' }}">
                                <button type="submit">
                                    <img src="{{ asset('frontend/assets/images/search_icon.png') }}" alt="Search" class="img-fluid">
                                </button>
                            </div>

                            <div class="wsus__sidebar_category">
                                <h3>{{ __('Categories') }}</h3>
                                <ul class="categoty_list">
                                    @foreach($categories as $category)
                                    <li class="active">{{ $category->name }}
                                        <div class="wsus__sidebar_sub_category">
                                            @foreach($category->subCategories as $subCategory)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $subCategory->id }}"
                                                    id="category-{{ $subCategory->id }}" name="category[]" @checked(
                                                    is_array(request()->category) ?
                                                    in_array($subCategory->id, request()->category ?? []):
                                                    $subCategory->id == request()->category
                                                    )>
                                                <label class="form-check-label" for="category-{{ $subCategory->id }}">
                                                    {{ $subCategory->name }}
                                                </label>
                                            </div>
                                            @endforeach

                                        </div>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>

                            <div class="wsus__sidebar_course_lavel">
                                <h3>{{ __('Difficulty Level') }}</h3>
                                @foreach($levels as $level)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $level->id }}" name="level[]" id="level-{{ $level->id }}" @checked(in_array($level->id, request()->level ?? [])) >
                                    <label class="form-check-label" for="level-{{ $level->id }}">
                                        {{ $level->name }}
                                    </label>
                                </div>
                                @endforeach

                            </div>





                            <div class="wsus__sidebar_course_lavel duration">
                                <h3>{{ __('Language') }}</h3>
                                @foreach($languages as $language)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $language->id }}" name="language[]" id="language-{{ $language->id }}" @checked(in_array($language->id, request()->language ?? []))>
                                    <label class="form-check-label" for="language-{{ $language->id }}">
                                        {{ $language->name }}
                                    </label>
                                </div>
                                @endforeach

                            </div>


                            <br>
                            <div class="row">
                                <button type="submit" class="common_btn">{{ __('Search') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 order-lg-1">
                    <div class="wsus__page_courses_header wow fadeInUp">
                        <!--
                        <p>Showing <span>1-{{ $courses->count() }}</span> Of <span>{{ $courses->total() }}</span> Results</p>

                        <form action="{{ localizedRoute('courses.index') }}">
                            <p>{{ __('Sort-by:') }}</p>
                            <select class="select_js" name="order" onchange="this.form.submit()">
                                <option value="desc" @selected(request()->order == 'desc')>{{ __('New to Old') }}</option>
                                <option value="asc" @selected(request()->order == 'asc')>{{ __('Old to New') }}</option>
                            </select>
                        </form>
                                                    -->
                    </div>
                    <div class="row">
                        @forelse($courses as $course)
                        <div class="col-xl-4 col-md-6">
                            <div class="wsus__single_courses_3">
                                <div class="wsus__single_courses_3_img">
                                    <img src="{{ asset($course->thumbnail) }}" alt="Courses" class="img-fluid">

                                    <span class="time"><i class="far fa-clock"></i> {{ convertMinutesToHours($course->duration) }}</span>
                                </div>
                                <div class="wsus__single_courses_text_3">


                                    <a class="title" href="{{ localizedRoute('courses.show', $course->translated_slug) }}">{{ $course->translated_title }}</a>
                                    <ul>
                                        <li>{{ $course->lessons()->count() }} {{ __('Lessons') }}</li>
                                        <li>{{ $course->enrollments()->count() }} {{ __('Student') }}</li>
                                    </ul>
                                    <a class="author" href="#">
                                        <div class="img">
                                            <img src="{{ asset($course->instructor->image) }}" alt="Author" class="img-fluid">
                                        </div>
                                        <h4>{{ $course->instructor->name }}</h4>
                                    </a>

                                    <div class="mt-3">
                                        @auth
                                            @if(auth()->user()->role == 'user')
                                                @php
                                                    $isEnrolled = auth()->user()->enrollments()->where('course_id', $course->id)->exists();
                                                @endphp

                                                @if($isEnrolled)
                                                    <a href="{{ route('enrolled-courses.index', ['locale' => app()->getLocale()]) }}"
                                                       class="common_btn w-100 text-center" style="background: #28a745;">
                                                        <i class="fas fa-check me-1"></i>
                                                        {{ __('Enrolled') }}
                                                    </a>
                                                @else
                                                    <button type="button"
                                                            class="common_btn w-100 add_to_cart"
                                                            data-course-id="{{ $course->id }}">
                                                        <i class="fas fa-user-plus me-1"></i>
                                                        {{ __('Enroll Now') }}
                                                    </button>
                                                @endif
                                            @endif
                                        @else
                                            <a href="{{ route('login', ['locale' => app()->getLocale()]) }}"
                                               class="common_btn w-100 text-center">
                                                <i class="fas fa-sign-in-alt me-1"></i>
                                                {{ __('Login to Enroll') }}
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>No data Found</p>
                        @endforelse
                    </div>
                    <div class="wsus__pagination mt_50 wow fadeInUp">
                        {{ $courses->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
        COURSES PAGE END
    ============================-->
@endsection
