@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Testimonial</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.testimonial-section.index',['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  
                                 class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12h14" />
                                <path d="M5 12l6 6" />
                                <path d="M5 12l6 -6" />
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.testimonial-section.update', [
                        'locale' => app()->getLocale(),
                        'testimonial' => $testimonial->id
                    ]) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-control">
                                @for($i = 5; $i >= 1; $i--)
                                    <option @selected($testimonial->rating == $i) value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-image-preview src="{{ asset($testimonial->user_image) }}" />
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image">

                            <input type="hidden" class="form-control" name="old_image"
                                   value="{{ $testimonial->user_image }}">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Russian (Required) -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">Русский (обязательно)</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Отзыв (RU)</label>
                                    <textarea name="translations[ru][review]" class="form-control" required>{{ $testimonial->translations->where('locale', 'ru')->first()?->review ?? '' }}</textarea>
                                    <x-input-error :messages="$errors->get('translations.ru.review')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Имя (RU)</label>
                                    <input type="text" class="form-control" name="translations[ru][name]" 
                                           value="{{ $testimonial->translations->where('locale', 'ru')->first()?->user_name ?? '' }}" required>
                                    <x-input-error :messages="$errors->get('translations.ru.name')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Должность (RU)</label>
                                    <input type="text" class="form-control" name="translations[ru][title]" 
                                           value="{{ $testimonial->translations->where('locale', 'ru')->first()?->user_title ?? '' }}" required>
                                    <x-input-error :messages="$errors->get('translations.ru.title')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Kazakh (Optional) -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">Қазақша (міндетті емес)</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Пікір (KK)</label>
                                    <textarea name="translations[kk][review]" class="form-control">{{ $testimonial->translations->where('locale', 'kk')->first()?->review ?? '' }}</textarea>
                                    <x-input-error :messages="$errors->get('translations.kk.review')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Аты (KK)</label>
                                    <input type="text" class="form-control" name="translations[kk][name]"
                                           value="{{ $testimonial->translations->where('locale', 'kk')->first()?->user_name ?? '' }}">
                                    <x-input-error :messages="$errors->get('translations.kk.name')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Лауазымы (KK)</label>
                                    <input type="text" class="form-control" name="translations[kk][title]"
                                           value="{{ $testimonial->translations->where('locale', 'kk')->first()?->user_title ?? '' }}">
                                    <x-input-error :messages="$errors->get('translations.kk.title')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- English (Optional) -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">English (Optional)</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Review (EN)</label>
                                    <textarea name="translations[en][review]" class="form-control">{{ $testimonial->translations->where('locale', 'en')->first()?->review ?? '' }}</textarea>
                                    <x-input-error :messages="$errors->get('translations.en.review')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name (EN)</label>
                                    <input type="text" class="form-control" name="translations[en][name]"
                                           value="{{ $testimonial->translations->where('locale', 'en')->first()?->user_name ?? '' }}">
                                    <x-input-error :messages="$errors->get('translations.en.name')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Title (EN)</label>
                                    <input type="text" class="form-control" name="translations[en][title]"
                                           value="{{ $testimonial->translations->where('locale', 'en')->first()?->user_title ?? '' }}">
                                    <x-input-error :messages="$errors->get('translations.en.title')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                     class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4v4h-6v-4" />
                                </svg>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
