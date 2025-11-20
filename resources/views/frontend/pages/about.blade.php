@extends('frontend.layouts.master')

@section('content')
    @php
        $aboutSections = \App\Models\AboutUs::with('translations')
            ->where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
    @endphp

    <!--===========================
            BREADCRUMB START
        ============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{__('About Us')}}</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">{{__('Home')}}</a></li>
                                <li>{{__('About Us')}}</li>
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
            ABOUT SECTIONS START
        ============================-->
    @forelse($aboutSections as $index => $section)
        <section class="wsus__about_3 {{ $index == 0 ? 'mt_120 xs_mt_100' : 'mt_100 xs_mt_80' }}">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    @if($section->image && $index % 2 == 0)
                        <!-- Image on left for even indices -->
                        <div class="col-lg-5 col-md-6 wow fadeInLeft">
                            <div class="wsus__about_img">
                                <img src="{{ asset($section->image) }}" alt="{{ $section->translated_title }}" class="img-fluid w-100">
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-{{ $section->image ? '6' : '12' }} col-md-{{ $section->image ? '6' : '12' }} wow fadeInRight">
                        <div class="wsus__about_text">
                            @if($section->translated_subtitle)
                                <span>{{ $section->translated_subtitle }}</span>
                            @endif
                            <h2>{{ $section->translated_title }}</h2>
                            <div class="description">
                                {!! $section->translated_description !!}
                            </div>
                        </div>
                    </div>

                    @if($section->image && $index % 2 != 0)
                        <!-- Image on right for odd indices -->
                        <div class="col-lg-5 col-md-6 wow fadeInRight">
                            <div class="wsus__about_img">
                                <img src="{{ asset($section->image) }}" alt="{{ $section->translated_title }}" class="img-fluid w-100">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @empty
        <section class="wsus__about_3 mt_120 xs_mt_100">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h3>{{__('Content is being prepared...')}}</h3>
                        <p>{{__('Please check back later')}}</p>
                    </div>
                </div>
            </div>
        </section>
    @endforelse
    <!--===========================
            ABOUT SECTIONS END
        ============================-->
@endsection
