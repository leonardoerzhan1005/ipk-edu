@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $session->exists ? 'Редактировать сессию' : 'Создать сессию' }}</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $action }}">
          @csrf
          @if($session->exists)
            @method('PUT')
          @endif

          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label required">Курс</label>
              <select class="form-select @error('course_id') is-invalid @enderror" name="course_id" required>
                <option value="">Выберите курс</option>
                @foreach($courses as $c)
                   <option value="{{ $c->id }}" @selected(($session->course_id ?? $prefCourseId)==$c->id)>
                     {{ $c->translated_title ?? $c->title }}
                   </option>
                @endforeach
              </select>
              @error('course_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Дата начала</label>
              <input class="form-control @error('start_date') is-invalid @enderror" type="date" name="start_date" value="{{ old('start_date', optional($session->start_date)->format('Y-m-d')) }}" required>
              @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Дата окончания</label>
              <input class="form-control @error('end_date') is-invalid @enderror" type="date" name="end_date" value="{{ old('end_date', optional($session->end_date)->format('Y-m-d')) }}" required>
              @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Формат</label>
              <select class="form-select @error('format') is-invalid @enderror" name="format" required>
                <option value="online" @selected(old('format', $session->format ?? 'online') == 'online')>Онлайн</option>
                <option value="offline" @selected(old('format', $session->format) == 'offline')>Оффлайн</option>
                <option value="hybrid" @selected(old('format', $session->format) == 'hybrid')>Гибрид</option>
              </select>
              @error('format')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Порядок сортировки</label>
              <input class="form-control @error('order') is-invalid @enderror" type="number" name="order" value="{{ old('order', $session->order ?? 0) }}">
              @error('order')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-hint">Чем меньше число, тем выше в списке</small>
            </div>

            <div class="col-md-6">
              <label class="form-label">&nbsp;</label>
              <label class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $session->is_active ?? true))>
                <span class="form-check-label">Активна</span>
              </label>
            </div>

            <!-- Переводы -->
            <div class="col-12">
              <hr>
              <h3 class="mb-3">Описание на разных языках</h3>
            </div>

            <!-- Русский язык (обязательный) -->
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">🇷🇺 Русский (основной)</h4>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea class="form-control @error('translations.ru.description') is-invalid @enderror"
                              name="translations[ru][description]"
                              rows="4"
                              placeholder="Краткое описание расписания курса на русском">{{ old('translations.ru.description', $session->translations->where('locale', 'ru')->first()?->description ?? '') }}</textarea>
                    @error('translations.ru.description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Казахский язык -->
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">🇰🇿 Қазақша</h4>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label">Сипаттама</label>
                    <textarea class="form-control @error('translations.kk.description') is-invalid @enderror"
                              name="translations[kk][description]"
                              rows="4"
                              placeholder="Қазақ тіліндегі қысқаша сипаттама">{{ old('translations.kk.description', $session->translations->where('locale', 'kk')->first()?->description ?? '') }}</textarea>
                    @error('translations.kk.description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Английский язык -->
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">🇬🇧 English</h4>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('translations.en.description') is-invalid @enderror"
                              name="translations[en][description]"
                              rows="4"
                              placeholder="Short description in English">{{ old('translations.en.description', $session->translations->where('locale', 'en')->first()?->description ?? '') }}</textarea>
                    @error('translations.en.description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12">
              <button class="btn btn-primary" type="submit">
                <i class="ti ti-device-floppy me-1"></i>
                Сохранить
              </button>
              <a class="btn btn-outline-secondary" href="{{ route('admin.course-sessions.index', ['locale'=>app()->getLocale()]) }}">
                <i class="ti ti-x me-1"></i>
                Отмена
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
