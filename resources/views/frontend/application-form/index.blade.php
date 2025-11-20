
@extends('frontend.layouts.master')

@section('contents')

 


<!-- ===========================
BREADCRUMB
=========================== -->
<section class="wsus__breadcrumb course_details_breadcrumb" >
  <div class="wsus__breadcrumb_overlay"  style="background-color:gray;color:black;">
    <div class="container">
      <div class="breadcrumb__text">
        <h1 class="breadcrumb__title">{{ __('Application form') }}</h1>
        <ul class="breadcrumb__list">
          <li><a class="breadcrumb__link" href="{{ url('/') }}">{{ __('Home') }}</a></li>
          <li>{{ __('Application form') }}</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ===========================
APPLICATION FORM
=========================== -->
<section class="application">
  <div class="container">
    <div class="application__wrapper">
      <header class="application__header">
        <h2 class="application__title">{{ __('Application form') }}</h2>
         
      </header>

      <!-- Language Switcher -->
      
      

      @if(session('success'))
        <div class="alert alert--success" role="alert">
          <i class="fas fa-check-circle" style="margin-right:6px"></i>
          {{ session('success') }}
          <button type="button" class="alert__close" data-bs-dismiss="alert">×</button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert--danger" role="alert">
          <i class="fas fa-exclamation-circle" style="margin-right:6px"></i>
          {{ session('error') }}
          <button type="button" class="alert__close" data-bs-dismiss="alert">×</button>
        </div>
      @endif

      <form method="POST" action="{{ localizedRoute('application-form.store') }}" class="form" id="applicationForm">
        @csrf
        <!-- Debug info -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <!-- Личные данные (автоматически по языку) -->
        <section class="form__section">
          <h4 class="form__section-title">
            <i class="fas fa-user"></i>
            {{ __('Personal data') }}  
          </h4>
          <div class="form__grid form__grid--3">
            <div class="form__group">
              <label class="form__label" for="last_name_{{ app()->getLocale() }}">{{ __('Last name') }} *</label>
              <input type="text" id="last_name_{{ app()->getLocale() }}" name="last_name_{{ app()->getLocale() }}"
                     class="form__control @error('last_name_' . app()->getLocale()) is-invalid @enderror"
                     value="{{ old('last_name_' . app()->getLocale()) }}"
                     placeholder="{{ __('Last name') }}" required>
              @error('last_name_' . app()->getLocale())
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form__group">
              <label class="form__label" for="first_name_{{ app()->getLocale() }}">{{ __('First name') }} *</label>
              <input type="text" id="first_name_{{ app()->getLocale() }}" name="first_name_{{ app()->getLocale() }}"
                     class="form__control @error('first_name_' . app()->getLocale()) is-invalid @enderror"
                     value="{{ old('first_name_' . app()->getLocale()) }}"
                     placeholder="{{ __('First name') }}" required>
              @error('first_name_' . app()->getLocale())
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form__group">
              <label class="form__label" for="middle_name_{{ app()->getLocale() }}">{{ __('Middle name') }}</label>
              <input type="text" id="middle_name_{{ app()->getLocale() }}" name="middle_name_{{ app()->getLocale() }}"
                     class="form__control @error('middle_name_' . app()->getLocale()) is-invalid @enderror"
                     value="{{ old('middle_name_' . app()->getLocale()) }}"
                     placeholder="{{ __('Middle name') }}">
              @error('middle_name_' . app()->getLocale())
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </section>

        <!-- Дополнительные поля для иностранцев -->
        <section class="form__section">
          <h4 class="form__section-title">
            <i class="fas fa-globe"></i>
            {{ __('Additional data for foreign citizens') }}
          </h4>
          <div class="form__grid form__grid--3">
            <div class="form__group">
              <label class="form__label" for="last_name_en">{{ __('Last name (English)') }}</label>
              <input type="text" id="last_name_en" name="last_name_en"
                     class="form__control @error('last_name_en') is-invalid @enderror"
                     value="{{ old('last_name_en') }}"
                     placeholder="{{ __('Last name in English') }}">
              @error('last_name_en')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form__group">
              <label class="form__label" for="first_name_en">{{ __('First name (English)') }}</label>
              <input type="text" id="first_name_en" name="first_name_en"
                     class="form__control @error('first_name_en') is-invalid @enderror"
                     value="{{ old('first_name_en') }}"
                     placeholder="{{ __('First name in English') }}">
              @error('first_name_en')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form__group">
              <label class="form__label" for="middle_name_en">{{ __('Middle name (English)') }}</label>
              <input type="text" id="middle_name_en" name="middle_name_en"
                     class="form__control @error('middle_name_en') is-invalid @enderror"
                     value="{{ old('middle_name_en') }}"
                     placeholder="{{ __('Middle name in English') }}">
              @error('middle_name_en')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </section>

        <!-- Гражданство -->
        <section class="form__section">
          <h4 class="form__section-title">
            <i class="fas fa-passport"></i>
            {{ __('Citizenship') }}
          </h4>
          <div class="form__group">
            <label class="form__label">{{ __('Are you a foreign citizen?') }} *</label>
            <div class="form__check">
              <input class="form__check-input" type="radio" name="is_foreign"
                     id="foreign_yes" value="1" {{ old('is_foreign') == '1' ? 'checked' : '' }} required>
              <label for="foreign_yes">{{ __('Yes') }}</label>
            </div>
            <div class="form__check">
              <input class="form__check-input" type="radio" name="is_foreign"
                     id="foreign_no" value="0" {{ old('is_foreign') == '0' ? 'checked' : '' }} required>
                <label for="foreign_no">{{ __('No') }}</label>
            </div>
            @error('is_foreign')
              <div class="form__feedback">{{ $message }}</div>
            @enderror
          </div>
        </section>

        <!-- Контактная информация -->
        <section class="form__section">
          <h4 class="form__section-title">
            <i class="fas fa-address-book"></i>
            {{ __('Contact information') }}
          </h4>
          <div class="form__grid form__grid--2">
            <div class="form__group">
              <label class="form__label" for="email">{{ __('E-mail') }} *</label>
              <input type="email" id="email" name="email"
                     class="form__control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}"
                     placeholder="{{ __('example@email.com') }}" required>
              @error('email')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="form__group">
              <label class="form__label" for="phone">{{ __('Mobile phone') }} *</label>
              <input type="tel" id="phone" name="phone"
                     class="form__control @error('phone') is-invalid @enderror"
                     value="{{ old('phone') }}"
                     placeholder="+7 (XXX) XXX-XX-XX" required>
              @error('phone')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </section>

        <!-- Образование и работа -->
        <section class="form__section">
          <h4 class="form__section-title">
            <i class="fas fa-graduation-cap"></i>
            {{ __('Education and work') }}
          </h4>

          <div class="form__grid form__grid--2">
            <div class="form__group">
              <label class="form__label" for="faculty_id">{{ __('Course direction') }}</label>
              <select id="faculty_id" name="faculty_id"
                      class="form__control no-nice-select @error('faculty_id') is-invalid @enderror">
                <option value="">{{ __('Select direction') }}</option>
                @if(isset($faculties) && $faculties && $faculties->count() > 0)
                  @foreach($faculties as $faculty)
                    <option value="{{ $faculty['id'] }}" {{ old('faculty_id') == $faculty['id'] ? 'selected' : '' }}>
                      {{ $faculty['name'] }}
                    </option>
                  @endforeach
                @else
                  <option value="" disabled>{{ __('No available directions') }}</option>
                @endif
              </select>
              @error('faculty_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form__group">
              <label class="form__label" for="specialty_id">{{ __('Specialization') }}</label>
              <select id="specialty_id" name="specialty_id"
                      class="form__control no-nice-select @error('specialty_id') is-invalid @enderror">
                <option value="">{{ __('Select direction first') }}</option>
                @if(isset($specialties) && $specialties && $specialties->count() > 0)
                  @foreach($specialties as $specialty)
                    <option value="{{ $specialty['id'] }}" {{ old('specialty_id') == $specialty['id'] ? 'selected' : '' }}>
                      {{ $specialty['name'] }}
                    </option>
                  @endforeach
                @endif
              </select>
              @error('specialty_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

     <!--     <div class="form__group">
            <label class="form__label">{{ __('Employment status') }} *</label>
            <div class="form__check">
              <input class="form__check-input" type="checkbox" id="is_unemployed" name="is_unemployed" value="1" {{ old('is_unemployed') ? 'checked' : '' }}>
              <label for="is_unemployed">{{ __('I am not working') }}</label>
            </div>
            @error('is_unemployed')
              <div class="form__feedback">{{ $message }}</div>
            @enderror
          </div>
    -->


        <!--  <div id="work_block" style="display: {{ old('is_unemployed') ? 'none' : 'block' }};"> -->
       <div>
            <div class="form__grid form__grid--2">
              <div class="form__group">
                <label class="form__label" for="workplace">{{ __('Workplace') }}</label>
                <input type="text" id="workplace" name="workplace"
                       class="form__control @error('workplace') is-invalid @enderror"
                       value="{{ old('workplace') }}"
                       placeholder="{{ __('Organization name') }}">
                @error('workplace')
                  <div class="form__feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form__group">
                <label class="form__label" for="org_type_id">{{ __('Institution category') }}</label>
                <select id="org_type_id" name="org_type_id"
                        class="form__control no-nice-select @error('org_type_id') is-invalid @enderror">
                  <option value="">{{ __('Select category') }}</option>
                  @if(isset($orgTypes) && $orgTypes && $orgTypes->count() > 0)
                    @foreach($orgTypes as $orgType)
                      <option value="{{ $orgType['id'] }}" {{ old('org_type_id') == $orgType['id'] ? 'selected' : '' }}>
                        {{ $orgType['name'] }}
                      </option>
                    @endforeach
                  @endif
                </select>
                @error('org_type_id')
                  <div class="form__feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="form__grid form__grid--2">
            <div class="form__group">
              <label class="form__label" for="country_id">{{ __('Country') }} *</label>
              <select id="country_id" name="country_id" class="form__control no-nice-select @error('country_id') is-invalid @enderror" required>
                <option value="">{{ __('Select country') }}</option>
                @if(isset($countries) && $countries && $countries->count() > 0)
                  @foreach($countries as $country)
                    <option value="{{ $country['id'] }}" data-code="{{ $country['code'] }}" {{ old('country_id') == $country['id'] ? 'selected' : '' }}>
                      {{ $country['name'] }}
                    </option>
                  @endforeach
                @endif
              </select>
              @error('country_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form__group">
              <label class="form__label" for="city_id">{{ __('City') }} *</label>
              <select id="city_id" name="city_id"
                      class="form__control no-nice-select @error('city_id') is-invalid @enderror" required>
                <option value="">{{ __('Select city') }}</option>
                @if(isset($cities) && $cities && $cities->count() > 0)
                  @foreach($cities as $city)
                    <option value="{{ $city['id'] }}" {{ old('city_id') == $city['id'] ? 'selected' : '' }}>
                      {{ $city['name'] }}
                    </option>
                  @endforeach
                @endif
              </select>
              @error('city_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form__group">
            <label class="form__label" for="address_line">{{ __('Address') }}</label>
            <input type="text" id="address_line" name="address_line"
                   class="form__control @error('address_line') is-invalid @enderror"
                   value="{{ old('address_line') }}"
                   placeholder="{{ __('Street, house, apartment') }}">
            @error('address_line')
              <div class="form__feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form__grid form__grid--2">
            <div class="form__group">
              <label class="form__label" for="degree_id">{{ __('Academic degree') }}</label>
              <select id="degree_id" name="degree_id"
                      class="form__control @error('degree_id') is-invalid @enderror">
                <option value="">{{ __('Select degree') }}</option>
                @if(isset($degrees) && $degrees && $degrees->count() > 0)
                  @foreach($degrees as $degree)
                    <option value="{{ $degree['id'] }}" {{ old('degree_id') == $degree['id'] ? 'selected' : '' }}>
                      {{ $degree['name'] }}
                    </option>
                  @endforeach
                @endif
              </select>
              @error('degree_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form__group">
              <label class="form__label" for="position">{{ __('Position') }}</label>
              <input type="text" id="position" name="position"
                     class="form__control @error('position') is-invalid @enderror"
                     value="{{ old('position') }}"
                     placeholder="{{ __('Enter position') }}">
              @error('position')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form__group">
            <label class="form__label" for="subjects">{{ __('Taught subjects') }}</label>
            <textarea id="subjects" name="subjects"
                      class="form__control @error('subjects') is-invalid @enderror"
                      placeholder="{{ __('Enter taught subjects separated by commas') }}"
                      rows="3">{{ old('subjects') }}</textarea>
            <small class="form__hint">{{ __('For example: Mathematics, Physics, Chemistry') }}</small>
            @error('subjects')
              <div class="form__feedback">{{ $message }}</div>
            @enderror
          </div>
        </section>

        <!-- Курс и язык -->
        <section class="form__section">
          <h4 class="form__section-title">
            <i class="fas fa-graduation-cap"></i>
            {{ __('Course and language') }}
          </h4>

          <div class="form__grid form__grid--2">
            <div class="form__group">
              <label class="form__label" for="course_id">{{ __('Course for professional development') }}</label>
              <select id="course_id" name="course_id"
                      class="form__control no-nice-select @error('course_id') is-invalid @enderror">
                <option value="">{{ __('Select course') }}</option>
                @if(isset($courses) && $courses && $courses->count() > 0)
                  @foreach($courses as $course)
                    <option value="{{ $course['id'] }}" {{ old('course_id') == $course['id'] ? 'selected' : '' }}>
                      {{ $course['name'] }}
                    </option>
                  @endforeach
                @endif
              </select>
              @error('course_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
              
              <!-- Поле для ввода курса вручную -->
              <div class="form__group" style="margin-top: 1rem;">
                <label class="form__label" for="custom_course_name">{{ __('Or enter your own course') }}</label>
                <textarea id="custom_course_name" name="custom_course_name"
                          class="form__control @error('custom_course_name') is-invalid @enderror"
                          placeholder="{{ __('Enter course name if not found in the list above') }}"
                          rows="2">{{ old('custom_course_name') }}</textarea>
                @error('custom_course_name')
                  <div class="form__feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="form__group">
              <label class="form__label" for="course_language_id">{{ __('Course language') }} *</label>
              <select id="course_language_id" name="course_language_id"
                      class="form__control no-nice-select @error('course_language_id') is-invalid @enderror" required>
                <option value="">{{ __('Select language') }}</option>
                @if(isset($courseLangs) && $courseLangs && $courseLangs->count() > 0)
                  @foreach($courseLangs as $language)
                    <option value="{{ $language['id'] }}" {{ old('course_language_id') == $language['id'] ? 'selected' : '' }}>
                      {{ $language['name'] }}
                    </option>
                  @endforeach
                @endif
              </select>
              @error('course_language_id')
                <div class="form__feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </section>

        <div class="form__submit">
          <button type="submit" class="button" id="submitBtn">
            <i class="fas fa-paper-plane" style="margin-right:6px"></i>
            {{ __('Submit application') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

 
 <style>
/* === Base / Vars (используем bootstrap-переменные с фолбэками) === */
:root{
  --c-body:#212529;
  --c-muted:#6c757d;
  --c-border:rgba(0,0,0,.175);
  --c-bg:#fff;
  --c-bg-soft:#f8f9fa;
  --radius: .375rem;
  --shadow: 0 .125rem .25rem rgba(0,0,0,.075);
  --primary: var(--bs-primary, #0d6efd);
  --success: var(--bs-success, #198754);
  --danger: var(--bs-danger, #dc3545);
}



/* === HERO / BREADCRUMB === */


/* === Card-wrapper для формы === */
.application{padding: 3rem 0;}
.application__wrapper{
  background: var(--c-bg);
  border:1px solid var(--c-border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 1.5rem;
}
@media (min-width: 992px){
  .application__wrapper{padding: 2rem;}
}
.application__header{margin-bottom: 1rem;}
.application__title{margin:0 0 .25rem; font-size:1.25rem; font-weight:600;}
.application__subtitle{margin:0; color: var(--c-muted);}

/* === Lang switcher === */


/* === Alerts (твоё .alert--success/.alert--danger → как bootstrap) === */
.alert{border-radius: var(--radius);}
.alert--success{background: rgba(25,135,84,.1); border:1px solid rgba(25,135,84,.25); color:#0f5132;}
.alert--danger{background: rgba(220,53,69,.1); border:1px solid rgba(220,53,69,.25); color:#842029;}
.alert__close{
  float:right; border:0; background:transparent; font-size:1.25rem; line-height:1; opacity:.5; cursor:pointer;
}
.alert__close:hover{opacity:.85;}

/* === Секции формы === */
.form__section{margin-top: 1.5rem;}
.form__section-title{
  display:flex; align-items:center; gap:.5rem;
  font-size: .95rem; font-weight:600; margin:0 0 .5rem;
}
.form__section-title i{color: var(--primary);}
.form__hint{color: var(--c-muted); font-size:.8125rem;}

/* === Grid (не меняя HTML): 3/2 колонки с авто-адаптацией === */
.form__grid{display:grid; gap:1rem;}
.form__grid--3{grid-template-columns: repeat(1,1fr);}
.form__grid--2{grid-template-columns: repeat(1,1fr);}
@media (min-width:768px){
  .form__grid--3{grid-template-columns: repeat(3,1fr);}
  .form__grid--2{grid-template-columns: repeat(2,1fr);}
}

/* === Группы и лейблы === */
.form__group{display:flex; flex-direction:column;}
.form__label{margin-bottom:.375rem; font-weight:500;}

/* === Поля ввода как Bootstrap .form-control === */
.form__control,
.form__control[type="date"],
.form__control[type="email"],
.form__control[type="tel"],
.form__control[type="text"],
.form__control select,
.form__control textarea{
  display:block;
  width:100%;
  padding:.375rem .75rem;
  font-size:1rem;
  line-height:1.5;
  color: var(--c-body);
  background-color:#fff;
  background-clip: padding-box;
  border:1px solid var(--c-border);
  border-radius: var(--radius);
  transition:border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
.form__control:focus{
  color: var(--c-body);
  background-color:#fff;
  border-color: rgba(13,110,253,.5);
  outline:0;
  box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
}

/* === Ошибки валидации (как .invalid-feedback) === */
.form__feedback{
  width:100%;
  margin-top:.25rem;
  font-size:.875em;
  color: var(--danger);
}

/* === Radio / Checkbox блоки === */
.form__check{display:flex; align-items:center; gap:.5rem; margin:.25rem 0;}
.form__check-input{width:1rem; height:1rem;}

/* === Кнопка отправки (твоё .button как .btn .btn-primary) === */
.button{
  display:inline-flex; align-items:center; gap:.375rem;
  border:1px solid var(--primary);
  background: var(--primary);
  color:#fff;
  padding:.5rem 1rem;
  border-radius: var(--radius);
  font-weight:600;
  text-decoration:none;
  transition: .15s ease-in-out;
}
.button:hover{filter: brightness(.95); color:#fff;}
.button:disabled{opacity:.65; cursor:not-allowed;}

.form__submit{margin-top:1.25rem;}

/* === Select2 под Bootstrap === */
.select2-container .select2-selection--single,
.select2-container .select2-selection--multiple{
  min-height: calc(2.25rem + 2px);
  border:1px solid var(--c-border)!important;
  border-radius: var(--radius)!important;
  padding:.25rem .375rem!important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
  line-height: calc(2.25rem);
}
.select2-container--default .select2-selection--single .select2-selection__arrow{
  height: calc(2.25rem);
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
  border-color: rgba(13,110,253,.5)!important;
  box-shadow: 0 0 0 .25rem rgba(13,110,253,.25)!important;
}
.select2-dropdown{border-color: var(--c-border)!important;}

/* === Декоративные мелочи === */
hr{opacity:.07;}

/* === Отключаем Nice Select для динамических полей === */
#faculty_id, #specialty_id, #course_id, #country_id, #city_id, #org_type_id, #course_language_id {
    display: block !important;
}

.nice-select {
    display: none !important;
}

/* === Стилизуем нативные select'ы === */
#faculty_id, #specialty_id, #course_id, #country_id, #city_id, #org_type_id, #course_language_id {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}

 </style>

<!-- JavaScript для динамических списков -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для чекбокса "Не работаю"
//    const unemployedCheckbox = document.getElementById('is_unemployed');
  //  const workBlock = document.getElementById('work_block');
    
    //if (unemployedCheckbox && workBlock) {
      //  unemployedCheckbox.addEventListener('change', function() {
        //    workBlock.style.display = this.checked ? 'none' : 'block';
            
            // Если отмечено "Не работаю", очищаем поля работы
          //  if (this.checked) {
            //    document.getElementById('workplace').value = '';
              //  document.getElementById('org_type_id').value = '';
            //}
        //});
   // }

    // Автоматическое определение языка из URL
    const currentLocale = window.location.pathname.split('/')[1]; // 'ru', 'kk', 'en'
    console.log('Current locale:', currentLocale);
    
    // Поле "Вы иностранный гражданин?" - просто флаг true/false, не влияет на отображение полей
    const locale = window.location.pathname.split('/')[1];
    // Динамическая загрузка специальностей при выборе факультета
    const facultySelect = document.getElementById('faculty_id');
    const specialtySelect = document.getElementById('specialty_id');
    const courseSelect = document.getElementById('course_id');
    const countrySelect = document.getElementById('country_id');
    const citySelect = document.getElementById('city_id');
    const orgTypeSelect = document.getElementById('org_type_id');
    const courseLanguageSelect = document.getElementById('course_language_id');

    const oldFaculty    = "{{ old('faculty_id') }}";
    const oldSpecialty  = "{{ old('specialty_id') }}";
    const oldCourse     = "{{ old('course_id') }}";
    const oldCountry    = "{{ old('country_id') }}";
    const oldCity       = "{{ old('city_id') }}";
    const oldOrgType    = "{{ old('org_type_id') }}";
    const oldCourseLanguage = "{{ old('course_language_id') }}";
    

    async function loadOptions(selectEl, url, placeholder, preselectId) {
    selectEl.innerHTML = `<option value="">${placeholder}</option>`;
    try {
      const res = await fetch(url);
      const data = await res.json();
      console.log('Loaded', url, data.length);
      data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.name;
        if (preselectId && String(item.id) === String(preselectId)) {
          opt.selected = true;
        }
        selectEl.appendChild(opt);
      });
    } catch (e) {
      console.error('Load failed', url, e);
    }
  }

  async function loadSpecialties(facultyId, preselectSpecialtyId) {
    await loadOptions(
      specialtySelect,
      `/${locale}/api/specialties?faculty_id=${facultyId}`,
      "{{ __('Select specialization') }}",
      preselectSpecialtyId
    );
  }

  async function loadCourses(specialtyId, preselectCourseId) {
    await loadOptions(
      courseSelect,
      `/${locale}/api/courses?specialty_id=${specialtyId}`,
      "{{ __('Select course') }}",
      preselectCourseId
    );
  }

  async function loadCities(countryId, preselectCityId) {
    await loadOptions(
      citySelect,
      `/${locale}/api/cities?country_id=${countryId}`,
      "{{ __('Select city') }}",
      preselectCityId
    );
  }

  async function loadOrgTypes(preselectOrgTypeId) {
    await loadOptions(
      orgTypeSelect,
      `/${locale}/api/org-types`,
      "{{ __('Select category') }}",
      preselectOrgTypeId
    );
  }

  async function loadCourseLanguages(preselectCourseLanguageId) {
    await loadOptions(
      courseLanguageSelect,
      `/${locale}/api/course-languages`,
      "{{ __('Select language') }}",
      preselectCourseLanguageId
    );
  }

  if (facultySelect && specialtySelect) {
    facultySelect.addEventListener('change', async function() {
      const facultyId = this.value;
      specialtySelect.innerHTML = `<option value="">{{ __('Select direction first') }}</option>`;
      courseSelect.innerHTML    = `<option value="">{{ __('Select specialization first') }}</option>`;
      if (facultyId) {
        await loadSpecialties(facultyId, null);
      }
    });
  }

  if (specialtySelect && courseSelect) {
    specialtySelect.addEventListener('change', async function() {
      const specialtyId = this.value;
      courseSelect.innerHTML = `<option value="">{{ __('Select specialization first') }}</option>`;
      if (specialtyId) {
        await loadCourses(specialtyId, null);
      }
    });
  }

  // Initial cascade for old() values
  (async function initCascade() {
    if (oldFaculty) {
      facultySelect.value = oldFaculty;
      await loadSpecialties(oldFaculty, oldSpecialty || null);
      if (oldSpecialty) {
        await loadCourses(oldSpecialty, oldCourse || null);
      }
    }
    
    if (oldCountry) {
      countrySelect.value = oldCountry;
      await loadCities(oldCountry, oldCity || null);
    }
    
    // Загружаем типы организаций при инициализации
    await loadOrgTypes(oldOrgType || null);
    
    // Загружаем языки курсов при инициализации
    await loadCourseLanguages(oldCourseLanguage || null);
  })();


 

    // Динамическая загрузка городов при выборе страны
    if (countrySelect && citySelect) {
        countrySelect.addEventListener('change', async function() {
            const countryId = this.value;
            citySelect.innerHTML = '<option value="">{{ __("Select city") }}</option>';
            if (countryId) {
                await loadCities(countryId, null);
            }
        });
    }

    // Динамическая загрузка курсов при выборе специальности
    if (specialtySelect && courseSelect) {
        specialtySelect.addEventListener('change', function() {
            const specialtyId = this.value;
            
            // Очищаем курсы
            courseSelect.innerHTML = '<option value="">{{ __("Select specialization first") }}</option>';
            
            if (specialtyId) {
                // Загружаем курсы для выбранной специальности
                fetch(`/${window.location.pathname.split('/')[1]}/api/courses?specialty_id=${specialtyId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            courseSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading courses:', error);
                    });
            }
        });
    }
});
</script> 
