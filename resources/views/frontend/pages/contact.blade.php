{{-- Contact Page --}}
@extends('frontend.layouts.master')

@section('content')
    <!-- Hero (blue) -->
    <section  class="wsus__breadcrumb course_details_breadcrumb" >
    <div class="wsus__breadcrumb_overlay">    
    <div class="container">
            <h1 class="fw-bold mb-2">{{ __('Contact') }}</h1>
            <p class="mb-0" style="opacity:.95;max-width:900px;">
                {{ __('Connect with the Institute for Advanced Training and Continuing Education') }}
            </p>
        </div>
        </div>
    </section>

    <!-- Content -->
    <section class="mt_120 xs_mt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6 wow fadeInLeft">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="mb-3">{{ __('Contact Information') }}</h3>
                            <p class="text-muted mb-4">{{ __('Get in touch with us using the information below.') }}</p>

                            <div class="row g-4">
                                <!-- Address -->
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-3 p-3 rounded border">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#EFF6FF;color:#1E3A8A;">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-1">{{ __('Address') }}</h5>
                                            <div class="text-muted">{{ __('Republic of Kazakhstan, 050040') }}</div>
                                            <div class="text-muted">{{ __('Almaty city, al-Farabi avenue, 71') }}</div>
                                            <div class="text-muted">{{ __('Al-Farabi KazNU, Building No.12, 3rd floor') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-3 p-3 rounded border">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#EFF6FF;color:#1E3A8A;">
                                            <i class="fas fa-phone-alt"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-1">{{ __('Phone') }}</h5>
                                            <div class="text-muted">+7 (727) 377-33-33 ({{ __('reception') }})</div>
                                            <div class="text-muted">+7 (727) 377-33-33 ({{ __('ext 1461') }})</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-3 p-3 rounded border">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#EFF6FF;color:#1E3A8A;">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-1">Email</h5>
                                            <div class="text-muted">info@kaznu.kz</div>
                                            <div class="text-muted">institute@kaznu.kz</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Working hours -->
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-3 p-3 rounded border">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#EFF6FF;color:#1E3A8A;">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <div>
                                            <h5 class="mb-1">{{ __('Working hours') }}</h5>
                                            <div class="text-muted">{{ __('Mon - Fri: 9:00 - 18:00') }}</div>
                                            <div class="text-muted">{{ __('Lunch break: 13:00 - 14:00') }}</div>
                                            <div class="text-muted">{{ __('Sat, Sun: closed') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInRight">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="mb-3">{{ __('Contact us') }}</h3>
                            <form action="{{ route('send.contact', ['locale' => request()->route('locale')]) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Full name') }} *</label>
                                        <input type="text" class="form-control" name="name" placeholder="{{ __('Enter your full name') }}" required>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Phone') }}</label>
                                        <input type="text" class="form-control" name="phone" placeholder="+7 (7XX) XXX-XX-XX">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Subject') }} *</label>
                                        <input type="text" class="form-control" name="subject" placeholder="{{ __('Message subject') }}" required>
                                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Message') }} *</label>
                                        <textarea class="form-control" name="message" rows="5" placeholder="{{ __('Enter your message') }}" required></textarea>
                                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="common_btn">{{ __('Send message') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($contactSetting?->map_url)
            <div class="row mt_60 wow fadeInUp">
                <div class="col-12">
                    <div class="ratio ratio-16x9 rounded overflow-hidden border">
                        {!! $contactSetting->map_url !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <!-- END -->
@endsection

{{-- Contact Page --}}