@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Category</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.blog-categories.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <i class="ti ti-arrow-left"></i>
                            Back 
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.blog-categories.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <x-input-toggle-block name="status" label="Status" />
                            </div>
                        </div>

                        <!-- Russian (Required) -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">Русский (обязательно)</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Название (RU)</label>
                                    <input type="text" name="translations[ru][name]" class="form-control" placeholder="Введите название" required>
                                    <x-input-error :messages="$errors->get('translations.ru.name')" class="mt-2" />
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
                                    <label class="form-label">Атауы (KK)</label>
                                    <input type="text" name="translations[kk][name]" class="form-control" placeholder="Атауды енгізіңіз">
                                    <x-input-error :messages="$errors->get('translations.kk.name')" class="mt-2" />
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
                                    <label class="form-label">Name (EN)</label>
                                    <input type="text" name="translations[en][name]" class="form-control" placeholder="Enter name">
                                    <x-input-error :messages="$errors->get('translations.en.name')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i class="ti ti-device-floppy"></i>  
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
