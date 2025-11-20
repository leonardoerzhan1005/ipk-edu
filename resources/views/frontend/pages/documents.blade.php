@extends('frontend.layouts.master')
@section('content')
<!--===========================
    BREADCRUMB START
============================-->
<section class="wsus__breadcrumb" style="background: url({{ asset('frontend/img/breadcrumb_bg.jpg') }});">
    <div class="wsus__breadcrumb_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="wsus__breadcrumb_text">
                        <h1>{{ __('Documents') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li>{{ __('Documents') }}</li>
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
    DOCUMENTS SECTION START
============================-->
<section class="wsus__documents mt_120 xs_mt_100 pb_120 xs_pb_100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wsus__section_header text-center mb_50">
                    <h2>{{ __('Documents') }}</h2>
                    <p>{{ __('Regulatory Documents, Orders, and Guidelines of the Institute for Professional Development and Continuing Education') }}</p>
                </div>

                <!-- Tabs Navigation -->
                <div class="wsus__documents_tabs mb_50">
                    <ul class="nav nav-tabs nav-fill" id="documentsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="normative-tab" data-bs-toggle="tab" data-bs-target="#normative" type="button" role="tab">
                                <i class="fas fa-file-contract me-2"></i>{{ __('Нормативтік құжаттар') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                                <i class="fas fa-file-signature me-2"></i>{{ __('Бұйрықтар') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="manuals-tab" data-bs-toggle="tab" data-bs-target="#manuals" type="button" role="tab">
                                <i class="fas fa-book me-2"></i>{{ __('Нұсқаулықтар') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab">
                                <i class="fas fa-file-alt me-2"></i>{{ __('Құжат үлгілері') }}
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="documentsTabContent">
                    <!-- Нормативтік құжаттар -->
                    <div class="tab-pane fade show active" id="normative" role="tabpanel">
                        <div class="row g-4">
                            @forelse(($documents['normative'] ?? collect()) as $doc)
                            <div class="col-lg-4 col-md-6">
                                <div class="wsus__document_card h-100">
                                    <div class="wsus__document_icon">
                                        <i class="fas fa-file-contract fa-2x"></i>
                                    </div>
                                    <div class="wsus__document_content">
                                        <h5 class="wsus__document_title">{{ $doc->title }}</h5>
                                        <p class="wsus__document_description">{{ $doc->description }}</p>
                                        <div class="wsus__document_meta">
                                            @if($doc->published_at)
                                            <span class="wsus__badge wsus__badge_light me-2">{{ __('Жарияланған күні') }}: {{ $doc->published_at->format('d.m.Y') }}</span>
                                            @endif
                                            @if($doc->file_format)
                                            <span class="wsus__badge wsus__badge_info me-2">{{ $doc->file_format }}</span>
                                            @endif
                                            @if($doc->file_size)
                                            <span class="wsus__badge wsus__badge_secondary">{{ $doc->file_size }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ asset($doc->file_path) }}" class="common_btn mt-3" target="_blank">
                                            <i class="fas fa-download me-2"></i>{{ __('Жүктеу') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center">{{ __('No data Found') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Бұйрықтар -->
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="row g-4">
                            @forelse(($documents['orders'] ?? collect()) as $doc)
                            <div class="col-lg-4 col-md-6">
                                <div class="wsus__document_card h-100">
                                    <div class="wsus__document_icon">
                                        <i class="fas fa-file-signature fa-2x"></i>
                                    </div>
                                    <div class="wsus__document_content">
                                        <h5 class="wsus__document_title">{{ $doc->title }}</h5>
                                        <p class="wsus__document_description">{{ $doc->description }}</p>
                                        <div class="wsus__document_meta">
                                            @if($doc->published_at)
                                            <span class="wsus__badge wsus__badge_light me-2">{{ __('Жарияланған күні') }}: {{ $doc->published_at->format('d.m.Y') }}</span>
                                            @endif
                                            @if($doc->file_format)
                                            <span class="wsus__badge wsus__badge_info me-2">{{ $doc->file_format }}</span>
                                            @endif
                                            @if($doc->file_size)
                                            <span class="wsus__badge wsus__badge_secondary">{{ $doc->file_size }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ asset($doc->file_path) }}" class="common_btn mt-3" target="_blank">
                                            <i class="fas fa-download me-2"></i>{{ __('Жүктеу') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center">{{ __('No data Found') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Нұсқаулықтар -->
                    <div class="tab-pane fade" id="manuals" role="tabpanel">
                        <div class="row g-4">
                            @forelse(($documents['manuals'] ?? collect()) as $doc)
                            <div class="col-lg-4 col-md-6">
                                <div class="wsus__document_card h-100">
                                    <div class="wsus__document_icon">
                                        <i class="fas fa-book fa-2x"></i>
                                    </div>
                                    <div class="wsus__document_content">
                                        <h5 class="wsus__document_title">{{ $doc->title }}</h5>
                                        <p class="wsus__document_description">{{ $doc->description }}</p>
                                        <div class="wsus__document_meta">
                                            @if($doc->published_at)
                                            <span class="wsus__badge wsus__badge_light me-2">{{ __('Жарияланған күні') }}: {{ $doc->published_at->format('d.m.Y') }}</span>
                                            @endif
                                            @if($doc->file_format)
                                            <span class="wsus__badge wsus__badge_info me-2">{{ $doc->file_format }}</span>
                                            @endif
                                            @if($doc->file_size)
                                            <span class="wsus__badge wsus__badge_secondary">{{ $doc->file_size }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ asset($doc->file_path) }}" class="common_btn mt-3" target="_blank">
                                            <i class="fas fa-download me-2"></i>{{ __('Жүктеу') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center">{{ __('No data Found') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Құжат үлгілері -->
                    <div class="tab-pane fade" id="templates" role="tabpanel">
                        <div class="row g-4">
                            @forelse(($documents['templates'] ?? collect()) as $doc)
                            <div class="col-lg-4 col-md-6">
                                <div class="wsus__document_card h-100">
                                    <div class="wsus__document_icon">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                    </div>
                                    <div class="wsus__document_content">
                                        <h5 class="wsus__document_title">{{ $doc->title }}</h5>
                                        <p class="wsus__document_description">{{ $doc->description }}</p>
                                        <div class="wsus__document_meta">
                                            @if($doc->published_at)
                                            <span class="wsus__badge wsus__badge_light me-2">{{ __('Жарияланған күні') }}: {{ $doc->published_at->format('d.m.Y') }}</span>
                                            @endif
                                            @if($doc->file_format)
                                            <span class="wsus__badge wsus__badge_success me-2">{{ $doc->file_format }}</span>
                                            @endif
                                            @if($doc->file_size)
                                            <span class="wsus__badge wsus__badge_secondary">{{ $doc->file_size }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ asset($doc->file_path) }}" class="common_btn mt-3" target="_blank">
                                            <i class="fas fa-download me-2"></i>{{ __('Жүктеу') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center">{{ __('No data Found') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===========================
    DOCUMENTS SECTION END
============================-->

<!--===========================
    HELP SECTION START
============================-->
<section class="wsus__help_section bg_light mt_120 xs_mt_100 pb_120 xs_pb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="wsus__help_content">
                    <div class="wsus__section_header">
                        <h3>{{ __('Құжаттарды табу') }}</h3>
                    </div>
                    <p class="lead mb-4">{{ __('Егер сізге қажетті құжатты таба алмасаңыз, бізбен байланысыңыз. Біз сізге көмектесеміз.') }}</p>
                    <a href="{{ localizedRoute('contact.index') }}" class="common_btn">
                        <i class="fas fa-question-circle me-2"></i>{{ __('Көмек алу') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===========================
    HELP SECTION END
============================-->
@endsection

@push('css')
<style>
.wsus__documents {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.wsus__document_card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.wsus__document_card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.wsus__document_icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 1.5rem;
}

.wsus__document_title {
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #2c3e50;
    font-size: 1.1rem;
    line-height: 1.4;
}

.wsus__document_description {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.6;
    flex-grow: 1;
}

.wsus__document_meta {
    margin-bottom: 1rem;
}

.wsus__badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
    border-radius: 12px;
    display: inline-block;
    margin-bottom: 0.25rem;
}

.wsus__badge_light {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
}

.wsus__badge_info {
    background: #17a2b8;
    color: white;
}

.wsus__badge_secondary {
    background: #6c757d;
    color: white;
}

.wsus__badge_success {
    background: #28a745;
    color: white;
}

.wsus__nav_tabs {
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 2rem;
}

.wsus__nav_tabs .nav-link {
    border: none;
    border-radius: 25px 25px 0 0;
    padding: 1rem 1.5rem;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.3s ease;
    background: transparent;
    margin-right: 0.5rem;
}

.wsus__nav_tabs .nav-link:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.wsus__nav_tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.tab-content {
    padding: 1rem 0;
}

.wsus__help_section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.wsus__help_content h3 {
    color: white;
}

.wsus__help_content p {
    color: rgba(255, 255, 255, 0.9);
}

.wsus__help_section .common_btn {
    background: white;
    color: #667eea;
    border: 2px solid white;
}

.wsus__help_section .common_btn:hover {
    background: transparent;
    color: white;
    border-color: white;
}

.wsus__section_header {
    margin-bottom: 2rem;
}

.wsus__section_header h2,
.wsus__section_header h3 {
    color: #2c3e50;
    font-weight: 600;
}

.wsus__section_header p {
    color: #6c757d;
    margin-top: 1rem;
}

.bg_light {
    background: #f8f9fa;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .wsus__document_card {
        padding: 1.5rem;
    }
    
    .wsus__document_icon {
        width: 50px;
        height: 50px;
        margin-bottom: 1rem;
    }
    
    .wsus__document_title {
        font-size: 1rem;
    }
    
    .wsus__nav_tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        margin-right: 0.25rem;
    }
}

@media (max-width: 576px) {
    .wsus__nav_tabs {
        flex-direction: column;
    }
    
    .wsus__nav_tabs .nav-link {
        border-radius: 25px;
        margin-bottom: 0.5rem;
        margin-right: 0;
    }
}
</style>
@endpush