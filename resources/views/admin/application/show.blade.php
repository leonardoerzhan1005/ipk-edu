@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            @php($locale = request()->route('locale') ?? app()->getLocale())

            <!-- Заголовок страницы -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                {{ __('Application') }} №{{ $application->id }}
                            </h2>
                            <div class="text-muted mt-1">
                                {{ __('Submitted') }} {{ $application->created_at->format('d.m.Y H:i') }}
                            </div>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="{{ route('admin.applications.index', ['locale' => $locale]) }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left"></i>
                                    {{ __('Back to list') }}
                                </a>
                                <button type="button" class="btn btn-primary" onclick="window.print()">
                                    <i class="ti ti-printer"></i>
                                    {{ __('Print') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Основная информация -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Main Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Full Name (Russian)') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->full_name_ru ?: $application->full_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Full Name (Kazakh)') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->full_name_kk ?: __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Full Name (English)') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->full_name_en ?: __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Foreign Citizen') }}</label>
                                        <div class="form-control-plaintext">
                                            @if($application->is_foreign)
                                                <span class="badge bg-info">{{ __('Yes') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('No') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <div class="form-control-plaintext">
                                            <a href="mailto:{{ $application->email }}">{{ $application->email }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Phone') }}</label>
                                        <div class="form-control-plaintext">
                                            <a href="tel:{{ $application->phone }}">{{ $application->phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Образовательная информация -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Educational Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Faculty') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->faculty->name ?? __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Specialty') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->specialty->name ?? __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Course') }}</label>
                                        <div class="form-control-plaintext">
                                            @if ($application->course)
                                                {{ $application->course->translated_title ?? $application->course->title }}
                                            @elseif ($application->custom_course_name)
                                                <div>
                                                    <span class="text-primary">{{ $application->custom_course_name }}</span>
                                                    <small class="text-muted d-block">{{ __('Custom course') }}</small>
                                                </div>
                                            @else
                                                {{ __('Not specified') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Course Language') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->courseLanguage->name ?? __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Рабочая информация -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Work Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Workplace') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->workplace ?: __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Organization Type') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->orgType->name ?? __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Unemployed') }}</label>
                                        <div class="form-control-plaintext">
                                            @if($application->is_unemployed)
                                                <span class="badge bg-warning">{{ __('Yes') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('No') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Position') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->position ?: __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($application->subjects)
                                <div class="mb-3">
                                    <label class="form-label text-muted">{{ __('Subjects/Skills') }}</label>
                                    <div class="form-control-plaintext">
                                        {{ $application->subjects }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Адресная информация -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Address Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('Country') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->country->name ?? __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('City') }}</label>
                                        <div class="form-control-plaintext">
                                            {{ $application->city->name ?? __('Not specified') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($application->address_line)
                                <div class="mb-3">
                                    <label class="form-label text-muted">{{ __('Address') }}</label>
                                    <div class="form-control-plaintext">
                                        {{ $application->address_line }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Образование -->
                    @if($application->degree)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Education') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted">{{ __('Academic Degree') }}</label>
                                    <div class="form-control-plaintext">
                                        {{ $application->degree->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Боковая панель -->
                <div class="col-lg-4">
                    <!-- Статус заявки -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Application Status') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Current Status') }}</label>
                                <div class="mb-2">
                                    @switch($application->status)
                                        @case('pending')
                                            <span class="badge bg-yellow text-yellow-fg fs-6">{{ __('Pending Review') }}</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-green text-green-fg fs-6">{{ __('Approved') }}</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-red text-red-fg fs-6">{{ __('Rejected') }}</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-blue text-blue-fg fs-6">{{ __('Completed') }}</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary fs-6">{{ $application->status }}</span>
                                    @endswitch
                                </div>
                                
                                <form action="{{ route('admin.applications.status.update', ['locale' => $locale, 'id' => $application->id]) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Change Status') }}</label>
                                        <select name="status" class="form-select">
                                            <option value="pending"   {{ $application->status === 'pending'   ? 'selected' : '' }}>{{ __('Pending Review') }}</option>
                                            <option value="approved"  {{ $application->status === 'approved'  ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                            <option value="rejected"  {{ $application->status === 'rejected'  ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                            <option value="completed" {{ $application->status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-check"></i>
                                        {{ __('Update Status') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Actions') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $application->email }}" class="btn btn-outline-primary">
                                    <i class="ti ti-mail"></i>
                                    {{ __('Send Email') }}
                                </a>
                                <a href="tel:{{ $application->phone }}" class="btn btn-outline-success">
                                    <i class="ti ti-phone"></i>
                                    {{ __('Call') }}
                                </a>
                                <form action="{{ route('admin.applications.destroy', ['locale' => $locale, 'id' => $application->id]) }}"
                                      method="POST"
                                      class="d-grid"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this application?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="ti ti-trash"></i>
                                        {{ __('Delete Application') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о пользователе -->
                    @if($application->user)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('User') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted">{{ __('User ID') }}</label>
                                    <div class="form-control-plaintext">
                                        {{ $application->user->id }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">{{ __('User Name') }}</label>
                                    <div class="form-control-plaintext">
                                        {{ $application->user->name }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">{{ __('User Email') }}</label>
                                    <div class="form-control-plaintext">
                                        {{ $application->user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
