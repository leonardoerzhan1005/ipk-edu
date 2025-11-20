<section class="pt_120 xs_pt_100 pb_60">
    <style>
        .service-card{border:1px solid rgba(21,59,138,.12)!important;transition:border-color .2s ease, border-width .2s ease, box-shadow .2s ease, transform .2s ease}
        .service-card:hover{border-width:2px!important;border-color:#153b8a!important;box-shadow:0 0.75rem 1.25rem rgba(0,0,0,.08)!important;transform:translateY(-2px)}
    </style>
    <div class="container">
        <div class="row">
            <div class="col-xl-8 m-auto wow fadeInUp">
                <div class="wsus__section_heading mb_30 text-center">
                    <h2 class="mb-2">{{ __('Our Services') }}</h2>
                    <p class="mb-0">{{ __('Enhance your professional competencies through the programs and courses we offer') }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php($list = ($services ?? collect()))
            @forelse($list as $idx => $service)
            <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-delay="{{ number_format($idx * 0.05, 2) }}s">
                <div class="card h-100 shadow-sm border-0 service-card">
                    <div class="ratio ratio-16x9">
                        @if($service->image)
                        <img src="{{ asset($service->image) }}" class="card-img-top object-fit-cover" alt="service"/>
                        @else
                        <img src="{{ asset('frontend/assets/images/courses_3_img_1.jpg') }}" class="card-img-top object-fit-cover" alt="service"/>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->translated_title }}</h5>
                        @if($service->translated_subtitle)
                        <p class="card-text mb-0">{{ $service->translated_subtitle }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">{{ __('No services available yet') }}</div>
            </div>
            @endforelse
        </div>
    </div>
</section>


