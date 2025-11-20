@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Редактировать курс: {{ $course->title }}</h3>
            <div class="card-actions">
              <a href="{{ route('admin.simple-courses.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>
                Назад к списку
              </a>
            </div>
          </div>

          <form action="{{ route('admin.simple-courses.update', ['locale' => app()->getLocale(), 'id' => $course->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
              <div class="row">
                <!-- Current Thumbnail -->
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label">Текущее изображение</label>
                    <div>
                      <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                  </div>
                </div>

                <!-- Basic Info -->
                <div class="col-12">
                  <h4 class="mb-3">Основная информация</h4>
                </div>

                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label required">Инструктор</label>
                    <select name="instructor_id" class="form-select @error('instructor_id') is-invalid @enderror" required>
                      <option value="">Выберите инструктора</option>
                      @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @selected(old('instructor_id', $course->instructor_id) == $instructor->id)>
                          {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                      @endforeach
                    </select>
                    @error('instructor_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label required">Категория</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                      <option value="">Выберите категорию</option>
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $course->category_id) == $category->id)>
                          {{ $category->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('category_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label required">Название курса</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" placeholder="Введите название курса" required>
                    @error('title')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label">SEO описание</label>
                    <input type="text" name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" value="{{ old('seo_description', $course->seo_description) }}" placeholder="Краткое описание для поисковых систем">
                    @error('seo_description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Изображение курса (оставьте пустым, чтобы сохранить текущее)</label>
                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                    <small class="form-hint">Рекомендуемый размер: 800x600px</small>
                    @error('thumbnail')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label required">Описание курса</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6" placeholder="Подробное описание курса" required>{{ old('description', $course->description) }}</textarea>
                    @error('description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <!-- Course Details -->
                <div class="col-12 mt-3">
                  <h4 class="mb-3">Детали курса</h4>
                </div>

                <div class="col-md-3">
                  <div class="mb-3">
                    <label class="form-label required">Цена ($)</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $course->price) }}" min="0" step="0.01" required>
                    <small class="form-hint">Укажите 0 для бесплатного курса</small>
                    @error('price')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="mb-3">
                    <label class="form-label">Скидка ($)</label>
                    <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $course->discount) }}" min="0" step="0.01">
                    @error('discount')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="mb-3">
                    <label class="form-label required">Длительность (часы)</label>
                    <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $course->duration) }}" min="1" required>
                    @error('duration')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="mb-3">
                    <label class="form-label">Вместимость</label>
                    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $course->capacity) }}" min="1" placeholder="Не ограничено">
                    @error('capacity')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Уровень</label>
                    <select name="level_id" class="form-select @error('level_id') is-invalid @enderror" required>
                      <option value="">Выберите уровень</option>
                      @foreach($levels as $level)
                        <option value="{{ $level->id }}" @selected(old('level_id', $course->course_level_id) == $level->id)>
                          {{ $level->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('level_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Язык</label>
                    <select name="language_id" class="form-select @error('language_id') is-invalid @enderror" required>
                      <option value="">Выберите язык</option>
                      @foreach($languages as $language)
                        <option value="{{ $language->id }}" @selected(old('language_id', $course->course_language_id) == $language->id)>
                          {{ $language->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('language_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Статус</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                      <option value="active" @selected(old('status', $course->status) == 'active')>Активный</option>
                      <option value="inactive" @selected(old('status', $course->status) == 'inactive')>Неактивный</option>
                      <option value="pending" @selected(old('status', $course->status) == 'pending')>Ожидает</option>
                    </select>
                    @error('status')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <!-- Options -->
                <div class="col-12 mt-2">
                  <div class="row">
                    <div class="col-md-4">
                      <label class="form-check">
                        <input type="checkbox" name="is_approved" value="1" class="form-check-input" @checked(old('is_approved', $course->is_approved))>
                        <span class="form-check-label">Одобрен</span>
                      </label>
                    </div>
                    <div class="col-md-4">
                      <label class="form-check">
                        <input type="checkbox" name="certificate" value="1" class="form-check-input" @checked(old('certificate', $course->certificate))>
                        <span class="form-check-label">Выдает сертификат</span>
                      </label>
                    </div>
                    <div class="col-md-4">
                      <label class="form-check">
                        <input type="checkbox" name="qna" value="1" class="form-check-input" @checked(old('qna', $course->qna))>
                        <span class="form-check-label">Q&A включен</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Translations -->
                <div class="col-12 mt-4">
                  <hr>
                  <h4 class="mb-3">Переводы (опционально)</h4>
                </div>

                @foreach(['ru' => '🇷🇺 Русский', 'kk' => '🇰🇿 Қазақша', 'en' => '🇬🇧 English'] as $locale => $label)
                @php
                  $translation = $course->translations->where('locale', $locale)->first();
                @endphp
                <div class="col-12">
                  <div class="card mb-3">
                    <div class="card-header">
                      <h5 class="card-title">{{ $label }}</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" name="translations[{{ $locale }}][title]" class="form-control" value="{{ old("translations.{$locale}.title", $translation?->title) }}" placeholder="Название на {{ $label }}">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">SEO описание</label>
                        <input type="text" name="translations[{{ $locale }}][seo_description]" class="form-control" value="{{ old("translations.{$locale}.seo_description", $translation?->seo_description) }}" placeholder="SEO описание на {{ $label }}">
                      </div>
                      <div class="mb-0">
                        <label class="form-label">Описание</label>
                        <textarea name="translations[{{ $locale }}][description]" class="form-control" rows="4" placeholder="Описание на {{ $label }}">{{ old("translations.{$locale}.description", $translation?->description) }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach

              </div>
            </div>

            <div class="card-footer">
              <div class="d-flex justify-content-between">
                <a href="{{ route('admin.simple-courses.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary">
                  <i class="ti ti-x me-1"></i>
                  Отмена
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="ti ti-device-floppy me-1"></i>
                  Сохранить изменения
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
