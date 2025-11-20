@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('Edit About Us Section')}}</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.about-us.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-primary">
                            <i class="ti ti-arrow-left"></i>
                            {{__('Back to List')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.about-us.update', ['locale' => app()->getLocale(), 'about_us' => $about_us->id]) }}" method="POST" enctype="multipart/form-data" id="about-form">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Progress Bar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{__('Translation Progress')}}</h6>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" id="translation-progress" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text">Заполните обязательные поля на русском языке</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <x-input-file-block name="image" label="{{__('Image (optional)')}}" />
                                @if($about_us->image)
                                    <div class="mt-2">
                                        <img src="{{ asset($about_us->image) }}" width="200" alt="Current image">
                                        <input type="hidden" name="old_image" value="{{ $about_us->image }}">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{__('Order')}}</label>
                                <input type="number" name="order" class="form-control" value="{{ old('order', $about_us->order) }}" min="0">
                                <small class="text-muted">Порядок отображения на странице</small>
                            </div>

                            <div class="col-md-3">
                                <x-input-toggle-block name="status" label="{{__('Status')}}" :checked="$about_us->status == 1" />
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        @php
                            $ruTranslation = $about_us->translations->firstWhere('locale', 'ru');
                            $kkTranslation = $about_us->translations->firstWhere('locale', 'kk');
                            $enTranslation = $about_us->translations->firstWhere('locale', 'en');
                        @endphp

                        <!-- Russian (Required) -->
                        <div class="card mb-3" id="ru-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Русский (обязательно)</h4>
                                <span class="badge bg-danger" id="ru-status">Не заполнено</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Заголовок (RU) *</label>
                                    <input type="text" name="translations[ru][title]" class="form-control" placeholder="Введите заголовок" value="{{ old('translations.ru.title', $ruTranslation?->title) }}">
                                    <x-input-error :messages="$errors->get('translations.ru.title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Подзаголовок (RU)</label>
                                    <input type="text" name="translations[ru][subtitle]" class="form-control" placeholder="Введите подзаголовок" value="{{ old('translations.ru.subtitle', $ruTranslation?->subtitle) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Описание (RU) *</label>
                                    <textarea name="translations[ru][description]" class="editor">{{ old('translations.ru.description', $ruTranslation?->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('translations.ru.description')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Kazakh (Optional) -->
                        <div class="card mb-3" id="kk-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Қазақша (міндетті емес)</h4>
                                <span class="badge bg-secondary" id="kk-status">Пропущено</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Тақырып (KK)</label>
                                    <input type="text" name="translations[kk][title]" class="form-control" placeholder="Тақырыпты енгізіңіз" value="{{ old('translations.kk.title', $kkTranslation?->title) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Қосымша тақырып (KK)</label>
                                    <input type="text" name="translations[kk][subtitle]" class="form-control" placeholder="Қосымша тақырыпты енгізіңіз" value="{{ old('translations.kk.subtitle', $kkTranslation?->subtitle) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Сипаттама (KK)</label>
                                    <textarea name="translations[kk][description]" class="editor">{{ old('translations.kk.description', $kkTranslation?->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- English (Optional) -->
                        <div class="card mb-3" id="en-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">English (Optional)</h4>
                                <span class="badge bg-secondary" id="en-status">Skipped</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Title (EN)</label>
                                    <input type="text" name="translations[en][title]" class="form-control" placeholder="Enter title" value="{{ old('translations.en.title', $enTranslation?->title) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subtitle (EN)</label>
                                    <input type="text" name="translations[en][subtitle]" class="form-control" placeholder="Enter subtitle" value="{{ old('translations.en.subtitle', $enTranslation?->subtitle) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description (EN)</label>
                                    <textarea name="translations[en][description]" class="editor">{{ old('translations.en.description', $enTranslation?->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i class="ti ti-device-floppy"></i>
                                {{__('Update')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card.border-danger {
            border-color: #dc3545 !important;
            border-width: 2px !important;
        }
        .card.border-success {
            border-color: #198754 !important;
            border-width: 2px !important;
        }
        .card.border-secondary {
            border-color: #6c757d !important;
            border-width: 1px !important;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Синхронизация TinyMCE с textarea перед отправкой формы
            document.getElementById('about-form').addEventListener('submit', function(e) {
                tinymce.triggerSave();

                // Кастомная валидация для обязательных полей
                const ruTitle = document.querySelector('input[name="translations[ru][title]"]');
                const ruDescription = document.querySelector('textarea[name="translations[ru][description]"]');

                if (!ruTitle.value.trim()) {
                    e.preventDefault();
                    alert('Заголовок на русском языке обязателен');
                    ruTitle.focus();
                    return false;
                }

                if (!ruDescription.value.trim()) {
                    e.preventDefault();
                    alert('Описание на русском языке обязательно');
                    ruDescription.focus();
                    return false;
                }
            });

            // Функция для проверки статуса языка
            function checkLanguageStatus(lang) {
                const titleField = document.querySelector(`input[name="translations[${lang}][title]"]`);
                const descriptionField = document.querySelector(`textarea[name="translations[${lang}][description]"]`);
                const statusBadge = document.getElementById(`${lang}-status`);
                const card = document.getElementById(`${lang}-card`);

                const hasTitle = titleField.value.trim() !== '';
                const hasDescription = descriptionField.value.trim() !== '';

                if (lang === 'ru') {
                    // Для русского языка нужны и заголовок, и описание
                    if (hasTitle && hasDescription) {
                        statusBadge.textContent = 'Заполнено';
                        statusBadge.className = 'badge bg-success';
                        card.classList.remove('border-danger');
                        card.classList.add('border-success');
                    } else {
                        statusBadge.textContent = 'Не заполнено';
                        statusBadge.className = 'badge bg-danger';
                        card.classList.remove('border-success');
                        card.classList.add('border-danger');
                    }
                } else {
                    // Для других языков достаточно заголовка или описания
                    if (hasTitle || hasDescription) {
                        statusBadge.textContent = lang === 'kk' ? 'Толтырылды' : 'Filled';
                        statusBadge.className = 'badge bg-success';
                        card.classList.remove('border-secondary');
                        card.classList.add('border-success');
                    } else {
                        statusBadge.textContent = lang === 'kk' ? 'Өткізілді' : 'Skipped';
                        statusBadge.className = 'badge bg-secondary';
                        card.classList.remove('border-success');
                        card.classList.add('border-secondary');
                    }
                }

                // Обновляем прогресс-бар
                updateTranslationProgress();
            }

            // Функция для обновления прогресс-бара
            function updateTranslationProgress() {
                const languages = ['ru', 'kk', 'en'];
                let completedLanguages = 0;
                let totalLanguages = 0;
                let progressText = '';

                languages.forEach(lang => {
                    const titleField = document.querySelector(`input[name="translations[${lang}][title]"]`);
                    const descriptionField = document.querySelector(`textarea[name="translations[${lang}][description]"]`);

                    const hasTitle = titleField.value.trim() !== '';
                    const hasDescription = descriptionField.value.trim() !== '';

                    if (lang === 'ru') {
                        // Русский обязателен
                        totalLanguages++;
                        if (hasTitle && hasDescription) {
                            completedLanguages++;
                        }
                    } else {
                        // Другие языки необязательны, но считаем их если заполнены
                        if (hasTitle || hasDescription) {
                            totalLanguages++;
                            completedLanguages++;
                        }
                    }
                });

                const progress = totalLanguages > 0 ? (completedLanguages / totalLanguages) * 100 : 0;
                const progressBar = document.getElementById('translation-progress');
                const progressTextEl = document.getElementById('progress-text');

                progressBar.style.width = progress + '%';

                if (progress === 100) {
                    progressBar.className = 'progress-bar bg-success';
                    progressText = 'Все переводы заполнены!';
                } else if (progress >= 50) {
                    progressBar.className = 'progress-bar bg-warning';
                    progressText = `Заполнено ${completedLanguages} из ${totalLanguages} языков`;
                } else {
                    progressBar.className = 'progress-bar bg-danger';
                    progressText = 'Заполните обязательные поля на русском языке';
                }

                progressTextEl.textContent = progressText;
            }

            // Добавляем обработчики событий для всех полей
            ['ru', 'kk', 'en'].forEach(lang => {
                const titleField = document.querySelector(`input[name="translations[${lang}][title]"]`);
                const descriptionField = document.querySelector(`textarea[name="translations[${lang}][description]"]`);

                if (titleField) {
                    titleField.addEventListener('input', () => checkLanguageStatus(lang));
                }
                if (descriptionField) {
                    descriptionField.addEventListener('input', () => checkLanguageStatus(lang));
                }

                // Проверяем статус при загрузке страницы
                checkLanguageStatus(lang);
            });
        });
    </script>
@endsection
