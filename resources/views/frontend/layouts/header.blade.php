   @php
       $topbar = \App\Models\TopBar::first();
       $categories = \App\Models\CourseCategory::whereNull('parent_id')->where('status', 1)->get();
       $customPages = \App\Models\CustomPage::where('status', 1)->where('show_at_nav', 1)->get();
       $locale = app()->getLocale();
   @endphp

   <!-- Тест JSON локализации -->

        <!-- Отладка локализации -->


     <!--===========================
          HEADER START
     ============================-->

   <!--===========================
        HEADER END
    ============================-->


   <!--===========================
        MAIN MENU 3 START
    ============================-->
   <nav class="navbar navbar-expand-lg main_menu main_menu_5">
	<style>
    /* Ensure the image doesn't overflow and scales properly */
    .navbar-brand img {
      max-width: 100%;
      height: auto;
      max-height: 55px; /* adjust to your liking */
      object-fit: contain;
    }

    /* Center the logo vertically in the navbar */
    .navbar-brand {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      margin-right: 1rem; /* add some spacing before menu items */
    }

    /* Optional hover effect */
  </style>

       <a class="navbar-brand" href="{{ url('/') }}">
           <img src="{{ asset(config('settings.site_logo')) }}" alt="ipk" class="img-fluid">
       </a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{--
           <div class="menu_category">
               <div class="icon">
                   <img src="{{ asset('frontend/assets/images/grid_icon.png') }}" alt="Category" class="img-fluid">
               </div>
               Category
               <ul>
                   @foreach ($categories as $category)
                       <li>
                           <a href="{{ localizedRoute('courses.index', ['main_category' => $category->slug]) }}">
                               <span>
                                   <img src="{{ asset($category->image) }}" alt="Category" class="img-fluid">
                               </span>
                               {{ $category->name }}
                           </a>
                           @if ($category->subCategories->count() > 0)
                               <ul class="category_sub_menu">
                                   @foreach ($category->subCategories as $subCategory)
                                       <li><a
                                               href="{{ localizedRoute('courses.index', ['category' => $subCategory->id]) }}">{{ $subCategory->name }}</a>
                                       </li>
                                   @endforeach

                               </ul>
                           @endif
                       </li>
                   @endforeach

               </ul>
           </div>
           --}}
           <ul class="navbar-nav m-auto">
               <li class="nav-item">
                   <a class="nav-link active" href="{{ url('/') }}">{{__('Home')}}</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('services') }}">{{__('Services')}}</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('application-form') }}">{{__('Application form')}}</a>
               </li>
       
         <!--
               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('courses.index') }}">{{__('Courses')}}</a>
               </li>
 	
	-->
               <li class="nav-item">
                   <a class="nav-link" href="{{ route('course-schedule.index', ['locale' => app()->getLocale()]) }}">{{__('Course Schedule')}}</a>
               </li>


               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('about.index') }}">{{__('About')}}</a>
               </li>


               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('documents') }}">{{__('Documents')}}</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('blog.index') }}">{{__('News')}}</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('certificates') }}">{{__('Certificates')}}</a>
               </li>

               <li class="nav-item">
                   <a class="nav-link" href="{{ localizedRoute('contact.index') }}">{{__('contact us')}}</a>
               </li>
               @foreach ($customPages as $page)
                   <li class="nav-item">
                       <a class="nav-link" href="{{ route('custom-page', $page->slug) }}">{{ $page->title }}</a>
                   </li>
               @endforeach
           </ul>
           <ul class="navbar-nav m-auto">
           <li class="nav-item d-flex align-items-center p-50 m-50 style="margin-left: 10px;">
                       <select id="locale-switcher" class="form-select">
                           <option  value="ru"  {{ app()->getLocale() === 'ru' ? 'selected' : '' }}>Рус</option>
                           <option value="kk" {{ app()->getLocale() === 'kk' ? 'selected' : '' }}>Қаз</option>
                           <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>Eng</option>
                       </select>
                   </li>



           </ul>



           <div class="right_menu">
               @if(config('feature.cart_enabled'))
               <div class="menu_search_btn">
                   <img src="{{ asset('frontend/assets/images/search_icon.png') }}" alt="Search" class="img-fluid">
               </div>
               <ul>
                   <li>
                       <a class="menu_signin" href="{{ localizedRoute('cart.index') }}">
                           <span>
                               <img src="{{ asset('frontend/assets/images/cart_icon_black.png') }}" alt="cart"
                                   class="img-fluid">
                           </span>
                           <b class="cart_count">{{ cartCount() }}</b>
                       </a>
                   </li>
               @endif

                   <li>
                    @if(!auth()->guard('web')->check())
                       <a class="common_btn" href="{{ route('login', ['locale' => app()->getLocale()]) }}">{{__('Sign in')}}</a>
                    @endif
                    @if(user()?->role == 'student')
                        <a class="common_btn" href="{{ route('student.dashboard') }}">{{__('Dashboard')}}</a>
                    @endif
                    @if(user()?->role == 'instructor')
                        <a class="common_btn" href="{{ route('instructor.dashboard') }}">{{__('Dashboard')}}</a>
                    @endif
                   </li>



               </ul>
           </div>

       </div>
   </nav>
   <div class="wsus__menu_3_search_area">
       <form action="{{ localizedRoute('courses.index') }}">
           <input type="text" placeholder="Search School, Online....." name="search">
           <button class="common_btn" type="submit">Search</button>
           <span class="close_search"><i class="far fa-times"></i></span>
       </form>
   </div>
   <!--===========================
        MAIN MENU 3 END
    ============================-->


   <!--============================
        MOBILE MENU START
    ==============================-->
   <div class="mobile_menu_area">
       <div class="mobile_menu_area_top">
           <a class="mobile_menu_logo" href="{{ url('/') }}">
               <img src="{{ asset(config('settings.site_logo')) }}" alt="{{ config('settings.site_title') }}">
           </a>
           <div class="mobile_menu_icon d-block d-lg-none" data-bs-toggle="offcanvas"
               data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
               <span class="mobile_menu_icon"><i class="far fa-stream menu_icon_bar"></i></span>
           </div>
       </div>

       <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions">
           <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                   class="fal fa-times"></i></button>
           <div class="offcanvas-body">

               <ul class="mobile_menu_header d-flex flex-wrap">
                   @if(config('feature.cart_enabled'))
                   <li><a href="{{ localizedRoute('cart.index') }}"><i class="far fa-shopping-basket"></i> <span class="cart_count">{{ cartCount() }}</span></a>
                   </li>
                   @endif
                   <li><a href="{{ route('login', ['locale' => app()->getLocale()]) }}"><i class="far fa-user"></i></a></li>
               </ul>

               <form class="mobile_menu_search" action="{{ localizedRoute('courses.index') }}">
                   <input type="text" placeholder="Search" name="search">
                   <button type="submit"><i class="far fa-search"></i></button>
               </form>

               <div class="mobile_menu_item_area">
                   <nav>
                       <div class="nav nav-tabs" id="nav-tab" role="tablist">
                           <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                               data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                               aria-selected="true">menu</button>
                           <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                               data-bs-target="#nav-profile" type="button" role="tab"
                               aria-controls="nav-profile" aria-selected="false">Categories</button>
                       </div>
                   </nav>
                   <div class="tab-content" id="nav-tabContent">
                       <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                           aria-labelledby="nav-home-tab" tabindex="0">
                           <ul class="main_mobile_menu">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('/') }}">{{__('Home')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ localizedRoute('courses.index') }}">{{__('Courses')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ localizedRoute('about.index') }}">{{__('About')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ localizedRoute('blog.index') }}">{{__('Blogs')}}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ localizedRoute('contact.index') }}">{{__('contact us')}}</a>
                            </li>
                            @foreach ($customPages as $page)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('custom-page', $page->slug) }}">{{ $page->title }}</a>
                                </li>
                            @endforeach
                           </ul>
                       </div>
                       <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                           tabindex="0">
                           <ul class="main_mobile_menu">
                            @foreach ($categories as $category)
                               <li class="mobile_dropdown">
                                   <a href="javascript:;">
                                       <span>
                                           <img src="{{ asset($category->image) }}" alt="Category"
                                               class="img-fluid">
                                       </span>
                                       {{ $category->name }}
                                   </a>
                                   @if ($category->subCategories->count() > 0)
                                   <ul class="inner_menu">
                                       @foreach ($category->subCategories as $subCategory)
                                       <li><a href="{{ localizedRoute('courses.index', ['category' => $subCategory->id]) }}">{{ $subCategory->name }}</a></li>
                                       @endforeach

                                   </ul>
                                   @endif
                               </li>
                           @endforeach


                               {{-- @foreach ($categories as $category)
                                   <li class="mobile_dropdown">
                                       <a href="javascript:;">
                                           <span>
                                               <img src="{{ asset($category->image) }}" alt="Category"
                                                   class="img-fluid">
                                           </span>
                                           {{ $category->name }}
                                       </a>
                                       @if ($category->subCategories->count() > 0)
                                           <ul class="category_sub_menu">
                                               @foreach ($category->subCategories as $subCategory)
                                                   <li><a
                                                           href="{{ route('courses.index', ['locale' => request()->route('locale'), 'category' => $subCategory->id]) }}">{{ $subCategory->name }}</a>
                                                   </li>
                                               @endforeach

                                           </ul>
                                       @endif
                                   </li>
                               @endforeach --}}




                           </ul>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
   <!--============================
        MOBILE MENU END
    ==============================-->


<style>
 #locale-switcher.form-select{
  appearance:none; -webkit-appearance:none; -moz-appearance:none;
  min-width:110px !important;

  padding:0.375rem 2rem !important; padding-right:2rem !important;
  border-radius:999px; border:1px solid rgba(0,0,0,.175);
  background-color: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  background-image: var(--bs-form-select-bg-img);        /* одна стрелка */
  background-position: right .65rem center; background-size:16px 12px;
}
#locale-switcher.form-select:focus{
  outline:0; border-color: rgba(13,110,253,.55);
  box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
}
</style>


@push('scripts')
<script>
  $(function(){
    var $sel = $('#locale-switcher');
    if ($.fn.niceSelect) { $sel.niceSelect(); }
    $sel.on('change', function(){
      try {
        var url = new URL(window.location.href);
        var parts = url.pathname.split('/');
        // ['', 'ru', 'path', ...] → индекс 1 — локаль
        if (parts.length > 1 && /^(en|ru|kk)$/.test(parts[1])) {
          parts[1] = this.value;
        } else {
          // если локали нет, добавим
          parts.splice(1, 0, this.value);
        }
        url.pathname = parts.join('/').replace(/\/\/+/, '/');
        window.location.href = url.toString();
      } catch (e) {
        // Фолбэк
        var path = window.location.pathname.replace(/^\/(en|ru|kk)(?=\/|$)/, '/' + this.value);
        if (!/^\/(en|ru|kk)(?=\/|$)/.test(window.location.pathname)) {
          path = '/' + this.value + (window.location.pathname.startsWith('/') ? '' : '/') + window.location.pathname;
        }
        window.location.href = path + window.location.search + window.location.hash;
      }
    });
  });
  // вместо $('select').niceSelect();
  $(function () {
  if ($('#locale-switcher').next('.nice-select').length) {
    $('#locale-switcher').niceSelect('destroy'); // вернёт нативный <select>
  }
  // курсор-«рука» для нативного селекта
  $('#locale-switcher').css('cursor','pointer');
});

$('select').not('.no-nice-select, #locale-switcher').niceSelect();
  </script>
@endpush
