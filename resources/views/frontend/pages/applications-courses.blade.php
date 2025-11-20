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

                            <div class="wsus__sidebar_course_lavel rating">
                                <h3>{{ __('Rating') }}</h3>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultr1">
                                    <label class="form-check-label" for="flexCheckDefaultr1">
                                        <i class="fas fa-star"></i> 5 star
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultr2">
                                    <label class="form-check-label" for="flexCheckDefaultr2">
                                        <i class="fas fa-star"></i> 4 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultr3">
                                    <label class="form-check-label" for="flexCheckDefaultr3">
                                        <i class="fas fa-star"></i> 3 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultr4">
                                    <label class="form-check-label" for="flexCheckDefaultr4">
                                        <i class="fas fa-star"></i> 2 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultr5">
                                    <label class="form-check-label" for="flexCheckDefaultr5">
                                        <i class="fas fa-star"></i> 1 star or above
                                    </label>
                                </div>
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

                            <div class="wsus__sidebar_rating">
                                <h3>{{ __('Price Range') }}</h3>
                                <div class="range_slider"></div>
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
                        <p>Showing <span>1-{{ $courses->count() }}</span> Of <span>{{ $courses->total() }}</span> Results</p>
                        
                        <form action="{{ localizedRoute('courses.index') }}">
                            <p>{{ __('Sort-by:') }}</p>
                            <select class="select_js" name="order" onchange="this.form.submit()">
                                <option value="desc" @selected(request()->order == 'desc')>{{ __('New to Old') }}</option>
                                <option value="asc" @selected(request()->order == 'asc')>{{ __('Old to New') }}</option>
                            </select>
                        </form>
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
                                    <div class="rating_area">
                                        <!-- <a href="#" class="category">Design</a> -->
                                        <p class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $course->reviews()->avg('rating'))
                                            <i class="fas fa-star"></i>
                                            @else
                                            <i class="far fa-star"></i>
                                            @endif  
                                               
                                            @endfor
                                            
                                            <span>({{ number_format($course->reviews()->avg('rating'), 2) ?? 0 }} Rating)</span>
                                        </p>
                                    </div>
    
                                    <a class="title" href="{{ localizedRoute('courses.show', $course->translated_slug) }}">{{ $course->translated_title }}</a>
                                    <ul>
                                        <li>{{ $course->lessons()->count() }} Lessons</li>
                                        <li>{{ $course->enrollments()->count() }} Student</li>
                                    </ul>
                                    <a class="author" href="#">
                                        <div class="img">
                                            <img src="{{ asset($course->instructor->image) }}" alt="Author" class="img-fluid">
                                        </div>
                                        <h4>{{ $course->instructor->name }}</h4>
                                    </a>
                                </div>
                                <div class="wsus__single_courses_3_footer">
                                    <button class="common_btn js-apply-course" type="button" data-course-id="{{ $course->id }}">Apply for a course<i class="far fa-arrow-right"></i></button>
                                    <p>
                                        @if($course->discount > 0)
                                        <del>${{ $course->price }}</del> ${{ $course->discount }}
                                        @else
                                        ${{ $course->price }}
                                        @endif
                                    </p>
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

    <!-- Dynamic Modal wrapper (reuse global #dynamic-modal from master) -->
    @push('scripts')
    <script>
      (function(){
        document.addEventListener('click', async function(e){
          const btn = e.target.closest('.js-apply-course');
          if(!btn) return;
          const courseId = btn.getAttribute('data-course-id');
          const locale = '{{ app()->getLocale() }}';
          const url = `/${locale}/courses/${courseId}/apply-modal`;
          const modalWrap = document.querySelector('#dynamic-modal .dynamic-modal-content');
          if(!modalWrap) return;
          modalWrap.innerHTML = '<div class="modal-content"><div class="modal-body">Loading...</div></div>';
          const resp = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
          const html = await resp.text();
          modalWrap.innerHTML = html;
          const m = new bootstrap.Modal(document.getElementById('dynamic-modal'));
          m.show();
        });
      })();
    </script>
    @endpush
    <!--===========================
        COURSES PAGE END
    ============================-->
@endsection
