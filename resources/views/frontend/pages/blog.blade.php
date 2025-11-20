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
                            <h1>{{ __('News') }}</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li>{{ __('News') }}</li>
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
            BLOG PAGE START
        ============================-->
    <section class="wsus__blog_page mt_95 xs_mt_75 pb_120 xs_pb_100">
        <div class="container">
            <!-- Category Filter -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">{{__('Filter by Category')}}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ localizedRoute('blog.index') }}" 
                                   class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{__('All Categories')}}
                                </a>
                                @foreach($blogCategories as $category)
                                    <a href="{{ localizedRoute('blog.index', ['category' => $category->slug]) }}" 
                                       class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $category->name }}
                                        <span class="badge bg-secondary ms-1">{{ $category->blogs_count ?? 0 }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                @forelse($blogs as $blog)
                @if($blog->translated_slug)
                <div class="col-xl-6 wow fadeInUp">
                    <div class="wsus__single_blog_4">
                        <a href="{{ localizedRoute('blog.show', ['slug' => $blog->translated_slug]) }}" class="wsus__single_blog_4_img">
                            <img src="{{ asset($blog->image) }}" alt="Blog" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="date">
                                {{ $blog->published_at
                                    ? $blog->published_at->translatedFormat('d F Y')
                                    : $blog->created_at->translatedFormat('d F Y') }}
                            </span>
                        </a>
                        <div class="wsus__single_blog_4_text">
                            <ul>
                                <li>
                                    <span><img src="{{ asset('frontend/assets/images/user_icon_black.png') }}" alt="User" class="img-fluid"></span>
                                    {{ __('By') }} {{ $blog->author->name }}
                                </li>
                                <li>
                                    <span><img src="{{ asset('frontend/assets/images/comment_icon_black.png') }}" alt="Comment" class="img-fluid"></span>
                                    {{ $blog->comments()->count() }} {{ __('Comments') }}
                                </li>
                                @if($blog->category)
                                <li>
                                    <span><i class="ti ti-category"></i></span>
                                    <a href="{{ localizedRoute('blog.index', ['category' => $blog->category->slug]) }}" class="text-primary">
                                        {{ $blog->category->name }}
                                    </a>
                                </li>
                                @endif
                            </ul>
                            <a href="{{ localizedRoute('blog.show', ['slug' => $blog->translated_slug]) }}" class="title">
                                {{ $blog->translated_title }}
                            </a>
                            <p>{{ Str::limit(strip_tags($blog->translated_description), 120) }}</p>
                            <a href="{{ localizedRoute('blog.show', ['slug' => $blog->translated_slug]) }}" class="common_btn">
                                {{ __('Read More') }} <i class="far fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @empty
                <div>{{ __('No Blog Found') }}</div>
                @endforelse
            </div>

            <div class="wsus__pagination mt_50 wow fadeInUp">
                {{ $blogs->links() }}
            </div>
        </div>
    </section>
    <!--===========================
            BLOG PAGE END
        ============================-->
@endsection
