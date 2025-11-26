@extends('frontend.layouts.master')

@section('content')
<div class="services-page-content">
    <!-- Hero Section -->
    <section class="wsus__breadcrumb course_details_breadcrumb">
        <div class="wsus__breadcrumb_overlay" style="background-color:#B1B1B1;color:#fff;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">{{ __('Services') }}</h1>
                    <p class="lead mb-4">{{ __('Services') }}</p>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section py-5">
        <div class="services-page-container">
            <div class="services-grid">
                @if(($services ?? collect())->isEmpty())
                    <div class="alert alert-info">{{ __('No services available yet') }}</div>
                @endif
                @foreach(($services ?? []) as $service)
                <div class="service-card">
                    <div class="service-header">
                      {{--
                        @if($service->image)
                        <div class="mb-2">
                            <img src="{{ asset($service->image) }}" alt="service" style="max-height:80px">
                        </div>
                        @endif
                        --}}
                        <h3>{{ $service->translated_title }}</h3>
                        @if($service->translated_subtitle)
                        <p>{{ $service->translated_subtitle }}</p>
                        @endif
                        @php($label = $service->button_label ?: __('Enroll course'))
                        @php($link = $service->button_link ?: localizedRoute('application-form'))
                        <a href="{{ $link }}" class="services-btn services-btn-primary">{{ $label }}</a>
                    </div>
                    @php($left = array_values(array_filter($service->translated_left_items ?? [])))
                    @php($right = array_values(array_filter($service->translated_right_items ?? [])))
                    @if(count($left))
                    <div class="service-content">
                        <ul class="course-list">
                            @foreach($left as $item)
                            <li><i class="fas fa-check"></i><span>{{ $item }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(count($right))
                    <div class="service-content-right">
                        <ul class="course-list-right">
                            @foreach($right as $item)
                            <li><i class="fas fa-check"></i><span>{{ $item }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Contact Section -->
    <section class="services-contact py-5">
        <div class="services-page-container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">{{ __('Need more info') }}</h2>
                    <p class="lead mb-4">{{ __('Contact for info') }}</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ localizedRoute('contact.index') }}" class="services-btn services-btn-outline-primary services-btn-lg services-w-100">
                                <i class="fas fa-phone me-2"></i>{{ __('Contact us') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ localizedRoute('application-form') }}" class="services-btn services-btn-primary services-btn-lg services-w-100">
                                <i class="fas fa-file-alt me-2"></i>{{ __('Submit application') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection



<style>
    /* Hero Section */
.services-hero {
  background: #1E3A8A; /* насыщенный синий */
  color: #fff;
  padding: 50px;
  margin-top: 100px;
  text-align: left;
}

.services-hero h1 {
  font-size: 2.25rem;
  font-weight: 700;
  margin-bottom: 1rem;
  text-align: center;
}

.services-hero p {
  font-size: 1.1rem;
  color: #e0e7ff;
}

/* Grid */
.services-grid {
  display: grid;
  grid-template-columns: 1fr;
  padding: 0 100px;
  gap: 2rem;
  margin-top: 2rem;
}

/* Card */
.service-card {
  display: flex;
  align-items: flex-start;
  background: #fff;
  border: 2px solid #B1B1B1;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  padding: 2rem;
  transition: box-shadow 0.2s ease;
}

.service-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  border: 2px solid #1E3A8A;
}

/* Header inside card */
.service-header {
  flex: 1;
}

.service-icon {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  background: #eff6ff;
  color: #1E3A8A;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.service-header h3 {
  color: #1E3A8A;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: .5rem;
}

.service-header p {
  color: #4b5563;
  margin-bottom: 1rem;
}

/* Course Content Box */
.service-content,
.service-content-right {
  background: #f9fafb;
  border-radius: 8px;
  padding: 1.25rem;
  flex: 1;
}

.course-list,
.course-list-right {
  list-style: none;
  margin: 0;
  padding: 0;
}

.course-list li,
.course-list-right li {
  margin-bottom: .5rem;
  color: #111827;
  font-size: .95rem;
}

.course-list li i,
.course-list-right li i {
  color: #1E3A8A;
  margin-right: .5rem;
}

/* Buttons */
.services-btn {
  display: inline-block;
  padding: .65rem 1.2rem;
  border-radius: 8px;
  font-weight: 500;
  font-size: .95rem;
  border: none;
  cursor: pointer;
  transition: background .2s ease;
}

.services-btn-primary {
  background: #1E3A8A;
  color: #fff;
}

.services-btn-primary:hover {
  background: #1e40af;
}

.services-btn-outline-primary {
  background: #fff;
  border: 1px solid #1E3A8A;
  color: #1E3A8A;
}

.services-btn-outline-primary:hover {
  background: #f0f5ff;
}

/* Responsive */
@media (min-width: 768px) {
  .services-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1200px) {
  .services-grid {
    grid-template-columns: 1fr;
  }
}


</style>
