@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Service</h4>
        <a href="{{ route('admin.services.index', ['locale' => app()->getLocale()]) }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('admin.services.update', ['locale' => app()->getLocale(), 'service' => $service->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    @if($service->image)
                        <div class="mb-2"><img src="{{ asset($service->image) }}" alt="image" style="max-height:80px"></div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                

                @php($tRu = optional($service->translations->firstWhere('locale','ru')))
                @php($tKk = optional($service->translations->firstWhere('locale','kk')))
                @php($tEn = optional($service->translations->firstWhere('locale','en')))

                <hr>
                <h5 class="mt-3">RU (обязательно)</h5>
                <div class="mb-3">
                    <label class="form-label">Заголовок</label>
                    <input type="text" name="translations[ru][title]" class="form-control @error('translations.ru.title') is-invalid @enderror" value="{{ old('translations.ru.title', $tRu->title) }}" required>
                    @error('translations.ru.title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Подзаголовок</label>
                    <input type="text" name="translations[ru][subtitle]" class="form-control" value="{{ old('translations.ru.subtitle', $tRu->subtitle) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Список слева</label>
                    <div id="left-items-ru">
                        @php($left = old('translations.ru.left_items', $tRu->left_items ?? []))
                        @foreach(($left ?: ['']) as $item)
                            <input type="text" name="translations[ru][left_items][]" class="form-control mb-2" value="{{ $item }}" placeholder="Элемент" />
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem('left-items-ru','translations[ru][left_items][]')">Добавить</button>
                    @error('translations.ru.left_items')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Список справа</label>
                    <div id="right-items-ru">
                        @php($right = old('translations.ru.right_items', $tRu->right_items ?? []))
                        @foreach(($right ?: ['']) as $item)
                            <input type="text" name="translations[ru][right_items][]" class="form-control mb-2" value="{{ $item }}" placeholder="Элемент" />
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem('right-items-ru','translations[ru][right_items][]')">Добавить</button>
                    @error('translations.ru.left_items')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <h5 class="mt-3">KK (необязательно)</h5>
                <div class="mb-3">
                    <label class="form-label">Тақырып</label>
                    <input type="text" name="translations[kk][title]" class="form-control" value="{{ old('translations.kk.title', $tKk->title) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Аст тақырып</label>
                    <input type="text" name="translations[kk][subtitle]" class="form-control" value="{{ old('translations.kk.subtitle', $tKk->subtitle) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Сол жақ тізім</label>
                    <div id="left-items-kk">
                        @php($leftKk = old('translations.kk.left_items', $tKk->left_items ?? []))
                        @foreach(($leftKk ?: ['']) as $item)
                            <input type="text" name="translations[kk][left_items][]" class="form-control mb-2" value="{{ $item }}" placeholder="Элемент" />
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem('left-items-kk','translations[kk][left_items][]')">Қосу</button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Оң жақ тізім</label>
                    <div id="right-items-kk">
                        @php($rightKk = old('translations.kk.right_items', $tKk->right_items ?? []))
                        @foreach(($rightKk ?: ['']) as $item)
                            <input type="text" name="translations[kk][right_items][]" class="form-control mb-2" value="{{ $item }}" placeholder="Элемент" />
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem('right-items-kk','translations[kk][right_items][]')">Қосу</button>
                </div>

                <hr>
                <h5 class="mt-3">EN (optional)</h5>
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="translations[en][title]" class="form-control" value="{{ old('translations.en.title', $tEn->title) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="translations[en][subtitle]" class="form-control" value="{{ old('translations.en.subtitle', $tEn->subtitle) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Left items</label>
                    <div id="left-items-en">
                        @php($leftEn = old('translations.en.left_items', $tEn->left_items ?? []))
                        @foreach(($leftEn ?: ['']) as $item)
                            <input type="text" name="translations[en][left_items][]" class="form-control mb-2" value="{{ $item }}" placeholder="Item" />
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem('left-items-en','translations[en][left_items][]')">Add</button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Right items</label>
                    <div id="right-items-en">
                        @php($rightEn = old('translations.en.right_items', $tEn->right_items ?? []))
                        @foreach(($rightEn ?: ['']) as $item)
                            <input type="text" name="translations[en][right_items][]" class="form-control mb-2" value="{{ $item }}" placeholder="Item" />
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem('right-items-en','translations[en][right_items][]')">Add</button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Button label</label>
                            <input type="text" name="button_label" class="form-control" value="{{ old('button_label', $service->button_label) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Button link</label>
                            <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $service->button_link) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Display order</label>
                            <input type="number" min="0" name="display_order" class="form-control" value="{{ old('display_order', $service->display_order) }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-3">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $service->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function addItem(containerId, nameAttr){
  const c = document.getElementById(containerId);
  const i = document.createElement('input');
  i.type = 'text';
  i.name = nameAttr;
  i.className = 'form-control mb-2';
  i.placeholder = 'Item';
  c.appendChild(i);
}
</script>
@endpush
@endsection


