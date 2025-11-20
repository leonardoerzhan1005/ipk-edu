@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('Create Blog')}}</h3>
                    <div class="card-actions">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.blogs.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-primary">
                                <i class="ti ti-arrow-left"></i>
                                {{__('Back to List')}}
                            </a>
                            <a href="{{ route('admin.blog-categories.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-info">
                                <i class="ti ti-category"></i>
                                {{__('Manage Categories')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.blogs.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data" id="blog-form">
                        @csrf
                        
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

                        <div class="row">
                            <div class="col-md-12">
                                <x-input-file-block name="image" label="Изображение (необязательно)" />
                                <small class="text-muted">Если не выберете изображение, будет использовано изображение по умолчанию</small>
                            </div>  

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="category">{{__('Category')}} <small class="text-muted">(необязательно)</small></label>
                                    <select name="category" id="category" class="form-control mt-2">
                                        <option value="">{{__('Without category')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                                @if($category->translations->count() > 1)
                                                    <small class="text-muted">
                                                        @php
                                                            $currentLocale = app()->getLocale();
                                                            $otherLocales = $category->translations->where('locale', '!=', $currentLocale)->pluck('locale')->join(', ');
                                                        @endphp
                                                        @if($otherLocales)
                                                            ({{ $otherLocales }})
                                                        @endif
                                                    </small>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle"></i>
                                        Если нужной категории нет, вы можете 
                                        <a href="{{ route('admin.blog-categories.create', ['locale' => app()->getLocale()]) }}" target="_blank">
                                            создать новую категорию
                                        </a>
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <x-input-toggle-block name="status" label="Status" />
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Russian (Required) -->
                        <div class="card mb-3" id="ru-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Русский (обязательно)</h4>
                                <span class="badge bg-danger" id="ru-status">Не заполнено</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Заголовок (RU)</label>
                                    <input type="text" name="translations[ru][title]" class="form-control" placeholder="Введите заголовок">
                                    <x-input-error :messages="$errors->get('translations.ru.title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Описание (RU)</label>
                                    <textarea name="translations[ru][description]" class="editor"></textarea>
                                    <x-input-error :messages="$errors->get('translations.ru.description')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Заголовок (RU)</label>
                                    <input type="text" name="translations[ru][seo_title]" class="form-control" placeholder="SEO заголовок">
                                    <x-input-error :messages="$errors->get('translations.ru.seo_title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Описание (RU)</label>
                                    <textarea name="translations[ru][seo_description]" class="form-control" placeholder="SEO описание"></textarea>
                                    <x-input-error :messages="$errors->get('translations.ru.seo_description')" class="mt-2" />
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
                                    <input type="text" name="translations[kk][title]" class="form-control" placeholder="Тақырыпты енгізіңіз">
                                    <x-input-error :messages="$errors->get('translations.kk.title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Сипаттама (KK)</label>
                                    <textarea name="translations[kk][description]" class="editor"></textarea>
                                    <x-input-error :messages="$errors->get('translations.kk.description')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Тақырып (KK)</label>
                                    <input type="text" name="translations[kk][seo_title]" class="form-control" placeholder="SEO тақырып">
                                    <x-input-error :messages="$errors->get('translations.kk.seo_title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Сипаттама (KK)</label>
                                    <textarea name="translations[kk][seo_description]" class="form-control" placeholder="SEO сипаттама"></textarea>
                                    <x-input-error :messages="$errors->get('translations.kk.seo_description')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- English (Optional) -->
                        <div class="card mb-3" id="en-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">English (Optional)</h4>
                                <span class="badge bg-secondary" id="en-status">Пропущено</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Title (EN)</label>
                                    <input type="text" name="translations[en][title]" class="form-control" placeholder="Enter title">
                                    <x-input-error :messages="$errors->get('translations.en.title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description (EN)</label>
                                    <textarea name="translations[en][description]" class="editor"></textarea>
                                    <x-input-error :messages="$errors->get('translations.en.description')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Title (EN)</label>
                                    <input type="text" name="translations[en][seo_title]" class="form-control" placeholder="SEO title">
                                    <x-input-error :messages="$errors->get('translations.en.seo_title')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Description (EN)</label>
                                    <textarea name="translations[en][seo_description]" class="form-control" placeholder="SEO description"></textarea>
                                    <x-input-error :messages="$errors->get('translations.en.seo_description')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
    <label class="form-label">Дата публикации</label>
    <input type="datetime-local" name="published_at" class="form-control"
        value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
    <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
</div>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i class="ti ti-device-floppy"></i>  
                                {{__('Create')}}
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
            document.getElementById('blog-form').addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                tinymce.triggerSave();
                console.log('TinyMCE content saved');
                
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
                
                console.log('Form validation passed, submitting...');
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
                        statusBadge.textContent = 'Заполнено';
                        statusBadge.className = 'badge bg-success';
                        card.classList.remove('border-secondary');
                        card.classList.add('border-success');
                    } else {
                        statusBadge.textContent = 'Пропущено';
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
