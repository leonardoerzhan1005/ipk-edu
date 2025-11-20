<div class="modal-content modal-apply-content shadow-lg">
<div class="modal-header">
  <h5 class="modal-title fw-semibold">{{ __('Application form') }} — {{ $course->translated_title ?? $course->title }}</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="POST" action="{{ route('application-form.store', ['locale'=>request()->route('locale')]) }}" class="p-0 m-0">
  @csrf
  <!-- Fixed course and optional links -->
  <input type="hidden" name="course_id" value="{{ $course->id }}">
  @if($course->faculty_id && \App\Models\Application\ApplicationFaculty::where('id',$course->faculty_id)->exists())
    <input type="hidden" name="faculty_id" value="{{ $course->faculty_id }}">
  @endif
  @if($course->specialty_id && \App\Models\Application\ApplicationSpecialty::where('id',$course->specialty_id)->exists())
    <input type="hidden" name="specialty_id" value="{{ $course->specialty_id }}">
  @endif

  <div class="modal-body course-apply">
    <div class="row g-3">
      <!-- Personal data (current locale) -->
      <div class="col-md-4">
        <label class="form-label" for="last_name_{{ app()->getLocale() }}">{{ __('Last name') }} *</label>
        <input class="form-control" id="last_name_{{ app()->getLocale() }}" name="last_name_{{ app()->getLocale() }}" placeholder="{{ __('Enter last name') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="first_name_{{ app()->getLocale() }}">{{ __('First name') }} *</label>
        <input class="form-control" id="first_name_{{ app()->getLocale() }}" name="first_name_{{ app()->getLocale() }}" placeholder="{{ __('Enter first name') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="middle_name_{{ app()->getLocale() }}">{{ __('Middle name') }}</label>
        <input class="form-control" id="middle_name_{{ app()->getLocale() }}" name="middle_name_{{ app()->getLocale() }}" placeholder="{{ __('Enter middle name') }}">
      </div>

      <!-- Citizenship -->
      <div class="col-12">
        <input type="hidden" name="is_unemployed" value="0">
        <label class="form-label d-block">{{ __('Are you a foreign citizen?') }} *</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="is_foreign" id="foreign_yes" value="1" required>
          <label class="form-check-label" for="foreign_yes">{{ __('Yes') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="is_foreign" id="foreign_no" value="0" required>
          <label class="form-check-label" for="foreign_no">{{ __('No') }}</label>
        </div>
      </div>

      <!-- Contacts -->
      <div class="col-md-6">
        <label class="form-label">Email *</label>
        <input class="form-control" name="email" type="email" value="{{ auth()->user()?->email }}" placeholder="example@email.com" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">{{ __('Mobile phone') }} *</label>
        <input class="form-control" name="phone" placeholder="+7 (XXX) XXX-XX-XX" required>
      </div>

      <!-- Employment -->
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_unemployed_modal" name="is_unemployed" value="1">
          <label class="form-check-label" for="is_unemployed_modal">{{ __('I am not working') }}</label>
        </div>
      </div>
      <div class="col-md-6" id="workplace_wrap_modal">
        <label class="form-label">{{ __('Workplace') }}</label>
        <input class="form-control" name="workplace" placeholder="{{ __('Organization name') }}">
      </div>
      <div class="col-md-6" id="orgtype_wrap_modal">
        <label class="form-label">{{ __('Institution category') }}</label>
        <select class="form-select" name="org_type_id">
          <option value="">{{ __('Select category') }}</option>
          @foreach(\App\Models\Application\ApplicationOrgType::with('translations')->get() as $orgType)
            <option value="{{ $orgType->id }}">{{ $orgType->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Country / City -->
      <div class="col-md-6">
        <label class="form-label" for="country_id_modal">{{ __('Country') }}</label>
        <select class="form-select" id="country_id_modal" name="country_id">
          <option value="">{{ __('Select country') }}</option>
          @foreach($countries as $country)
            <option value="{{ $country->id }}" data-code="{{ $country->code }}">{{ $country->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="city_id_modal">{{ __('City') }}</label>
        <select class="form-select" id="city_id_modal" name="city_id">
          <option value="">{{ __('Select city') }}</option>
          @foreach($cities as $city)
            <option value="{{ $city->id }}">{{ $city->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Address -->
      <div class="col-12">
        <label class="form-label" for="address_line_modal">{{ __('Address') }}</label>
        <input class="form-control" id="address_line_modal" name="address_line" placeholder="{{ __('Street, house, apartment') }}">
      </div>

      <!-- Degree / Position / Subjects -->
      <div class="col-md-6">
        <label class="form-label" for="degree_id_modal">{{ __('Academic degree') }}</label>
        <select class="form-select" id="degree_id_modal" name="degree_id">
          <option value="">{{ __('Select degree') }}</option>
          @foreach($degrees as $degree)
            <option value="{{ $degree->id }}">{{ $degree->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Session (dates) / Course language -->
      <div class="col-md-6">
        <label class="form-label" for="course_session_id_modal">{{ __('Choose session (dates)') }}</label>
        <select class="form-select" id="course_session_id_modal" name="course_session_id">
          <option value="">{{ __('Any available') }}</option>
          @foreach($sessions as $s)
            <option value="{{ $s->id }}">{{ $s->start_date->format('Y-m-d') }} — {{ $s->end_date->format('Y-m-d') }}</option>
          @endforeach
        </select>
      </div>

      <!-- Course language -->
      <div class="col-md-6">
        <label class="form-label">{{ __('Course language') }} *</label>
        <select class="form-select" name="course_language_id" required>
          <option value="">{{ __('Select language') }}</option>
          @foreach($courseLanguages as $l)
            <option value="{{ $l->id }}">{{ $l->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Optional extras -->
      <div class="col-md-6">
        <label class="form-label">{{ __('Position') }}</label>
        <input class="form-control" name="position" placeholder="{{ __('Your position') }}">
      </div>
      <div class="col-12">
        <label class="form-label">{{ __('Taught subjects') }}</label>
        <textarea class="form-control" name="subjects" rows="2" placeholder="{{ __('Enter subjects separated by commas') }}"></textarea>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button class="btn btn-primary" type="submit">{{ __('Submit application') }}</button>
  </div>
</form>
 </div>

<script>
  (function(){
    const cb = document.getElementById('is_unemployed_modal');
    const wp = document.getElementById('workplace_wrap_modal');
    const ot = document.getElementById('orgtype_wrap_modal');
    const country = document.getElementById('country_id_modal');
    const city = document.getElementById('city_id_modal');
    if(cb && wp && ot){
      const toggle = ()=>{ const off = cb.checked; wp.style.display = off?'none':'block'; ot.style.display = off?'none':'block'; };
      cb.addEventListener('change', toggle); toggle();
    }
    if(country && city){
      country.addEventListener('change', async function(){
        const code = this.options[this.selectedIndex]?.getAttribute('data-code');
        city.innerHTML = '<option value="">{{ __('Select city') }}</option>';
        if(!this.value) return;
        try{
          const locale = '{{ app()->getLocale() }}';
          const resp = await fetch(`/${locale}/api/cities?country_id=${this.value}`, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
          const data = await resp.json();
          data.forEach(function(item){
            const opt = document.createElement('option');
            opt.value = item.id; opt.textContent = item.name; city.appendChild(opt);
          });
        }catch(e){ console.error(e); }
      });
    }
  })();
</script>

<style>
  /* Professional, modern styling scoped to the modal only */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Merriweather:wght@700&display=swap');

:root{
  --blue:#25297b;
  --blue-dark:#1c1e57;
  --bg-soft:#f5f8ff;
  --ink:#0f1730;
  --border:#cfd4e2;
  --border-hover:#b8bfd2;
  --ring:rgba(37,41,123,.22);
  --ring-weak:rgba(37,41,123,.14);
  --shadow-soft:#e7eefc;
}

/* Container */
.modal-apply-content{
  background:#fff;
  border:3px solid var(--blue-dark);
  border-radius:40px;
  overflow:hidden;
  box-shadow:12px 12px var(--shadow-soft);
  font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
  color:var(--ink);
  animation:modalPop .18s ease-out both;
}

@keyframes modalPop{
  from{ transform:translateY(6px) scale(.98); opacity:.85; }
  to{ transform:none; opacity:1; }
}

/* Header */
.modal-apply-content .modal-header{
  background:#fff;
  border-bottom:1px solid rgba(2,6,23,.06);
  padding:24px 32px;
}
.modal-apply-content .modal-title{
  font-size:28px;
  font-family:'Merriweather',serif;
  font-weight:700;
  color:var(--blue);
}

/* Body */
.course-apply{ background:#fff; padding:8px 32px 24px; }
.course-apply .form-label{
  font-weight:600;
  font-size:16px;
  margin-bottom:.375rem;
  color:var(--ink);
}

/* Inputs/selects */
.course-apply .form-control,
.course-apply .form-select{
  border-radius:10px;
  border:1px solid var(--border);
  background-color:#fff;
  padding:10px 12px;
  transition:border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
}
.course-apply .form-control:hover,
.course-apply .form-select:hover{
  border-color:var(--border-hover);
  background-color:#fcfdff;
}
.course-apply .form-control:focus,
.course-apply .form-select:focus{
  border-color:var(--blue);
  box-shadow:0 0 0 .22rem var(--ring);
  outline:0;
}

/* Radios & checkboxes */
.course-apply .form-check{
  display:flex;
  align-items:center;
  gap:.5rem;
}

/* Делает radio и checkbox одинакового размера */
.course-apply .form-check-input{
  width: 1.2rem !important;
  height: 1.2rem !important;
  cursor: pointer !important;
  border: 1.5px solid var(--border) !important;
  accent-color: var(--blue-dark) !important;
  margin: 0 !important;
  box-shadow: none !important;
  transition: box-shadow .15s ease, border-color .15s ease !important;
}

/* Радио – круглые */
.course-apply .form-check-input[type="radio"]{
  border-radius: 50% !important;
}
/* Чекбоксы – квадратные */
.course-apply .form-check-input[type="checkbox"]{
  border-radius: 4px !important;
}

.course-apply .form-check-input:checked{
  border-color: var(--blue-dark) !important;
  background-color: var(--blue-dark) !important;
}
.course-apply .form-check-input:focus{
  box-shadow: 0 0 0 .2rem var(--ring-weak) !important;
  border-color: var(--blue) !important;
}
.course-apply .form-check-label{ cursor:pointer; }

/* Инлайновое отображение (в т.ч. для "I am not working") */
.course-apply .form-check-inline{
  display:inline-flex !important;
  align-items:center;
  gap:.5rem;
  margin-right:1rem;
  vertical-align:middle;
}

/* Footer */
.modal-apply-content .modal-footer{
  border-top:1px solid rgba(2,6,23,.06);
  padding:20px 32px 28px;
}

/* Buttons */
.modal-apply-content .btn{
  border-radius:10px;
  font-weight:600;
  line-height:1.2;
}
.modal-apply-content .btn-primary{
  background:var(--blue);
  color:#fff;
  border:3px solid var(--blue-dark);
  padding:.8rem 1.8rem;
  box-shadow:0 6px 0 var(--shadow-soft);
}
.modal-apply-content .btn-primary:hover{
  background:var(--blue-dark);
}
.modal-apply-content .btn:not(.btn-primary){
  background:var(--bg-soft);
  color:var(--blue);
  border:1px solid var(--blue-dark);
}
.modal-apply-content .btn:not(.btn-primary):hover{
  background:#e0e6fb;
}

/* Helpers */
.course-apply .text-muted{ color:#64748b !important; }

@media (max-width: 575.98px){
  .course-apply .btn-primary{ width:100%; }
  .modal-apply-content .modal-header,
  .course-apply,
  .modal-apply-content .modal-footer{
    padding-left:20px;
    padding-right:20px;
  }
}

/* Reduced motion preference */
@media (prefers-reduced-motion: reduce){
  .modal-apply-content{ animation:none; }
  .course-apply .form-control,
  .course-apply .form-select{ transition:none; }
}

/* Optional: invalid/valid states */
.course-apply .form-control.is-invalid,
.course-apply .form-select.is-invalid{
  border-color:#dc3545 !important;
  box-shadow:0 0 0 .22rem rgba(220,53,69,.15) !important;
}
.course-apply .form-control.is-valid,
.course-apply .form-select.is-valid{
  border-color:#198754 !important;
  box-shadow:0 0 0 .22rem rgba(25,135,84,.15) !important;
}

</style>


