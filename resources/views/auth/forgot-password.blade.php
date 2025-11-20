@extends('frontend.layouts.master')

@section('content')
    <!--===========================
        SIGN IN START
    ============================-->
    <section class="wsus__sign_in">
        <div class="row align-items-center">
            <div class="col-xxl-5 col-xl-6 col-lg-6 wow fadeInLeft">
                <div class="wsus__sign_img">
                     
                    
                </div>
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-9 m-auto wow fadeInRight">
                <div class="wsus__sign_form_area">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form action="{{ route('password.email', ['locale' => app()->getLocale()]) }}" method="POST">
                                @csrf
                                <h2>{{__('Forget Password')}}<span>!</span></h2>
                                <p class="new_user">{{__('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')}}</p>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="wsus__login_form_input">
                                            <label>{{__('Email')}} *</label>
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                            <x-input-error :messages="$errors->get('email', 'email')" class="mt-2" />
                                        </div>
                                    </div>
                                         
                                    <div class="col-xl-12">
                                        <div class="wsus__login_form_input">
                                            
                                            <button type="submit" class="common_btn">{{__('Email Password Reset Link')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <p class="create_account">{{__("Don't have an account?")}} <a href="{{ route('register', ['locale' => app()->getLocale()]) }}">{{__('Create free account')}}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="back_btn" href="index.html">Back to Home</a>
    </section>
    <!--===========================
        SIGN IN END
    ============================-->
@endsection
