@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            @php($locale = request()->route('locale') ?? app()->getLocale())

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Training Applications') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{ __('№') }}</th>
                                    <th>{{ __('Full Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Faculty') }}</th>
                                    <th>{{ __('Specialty') }}</th>
                                    <th>{{ __('Course') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Application Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>{{ $application->full_name }}</strong>
                                                @if ($application->is_foreign)
                                                    <small class="text-muted">{{ __('Foreign Citizen') }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $application->email }}</td>
                                        <td>{{ $application->phone }}</td>
                                        <td>
                                            @if ($application->faculty)
                                                {{ $application->faculty->name }}
                                            @else
                                                <span class="text-muted">{{ __('Not specified') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($application->specialty)
                                                {{ $application->specialty->name }}
                                            @else
                                                <span class="text-muted">{{ __('Not specified') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($application->course)
                                                {{ $application->course->translated_title ?? $application->course->title }}
                                            @elseif ($application->custom_course_name)
                                                <div class="d-flex flex-column">
                                                    <span class="text-primary">{{ $application->custom_course_name }}</span>
                                                    <small class="text-muted">{{ __('Custom course') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('Not specified') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($application->status)
                                                @case('pending')
                                                    <span class="badge bg-yellow text-yellow-fg">{{ __('Pending Review') }}</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-green text-green-fg">{{ __('Approved') }}</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-red text-red-fg">{{ __('Rejected') }}</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-blue text-blue-fg">{{ __('Completed') }}</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $application->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $application->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.applications.show',['locale' => $locale, 'id' => $application->id]) }}"
                                                   class="btn btn-sm btn-primary" title="{{ __('View') }}">
                                                    <i class="ti ti-eye"></i>
                                                </a>

                                                <button type="button"
                                                        class="btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>

                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('admin.applications.status.update', ['locale' => $locale, 'id' => $application->id]) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="ti ti-check"></i> {{ __('Approve') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.applications.status.update', ['locale' => $locale, 'id' => $application->id]) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-x"></i> {{ __('Reject') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.applications.status.update', ['locale' => $locale, 'id' => $application->id]) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="dropdown-item text-info">
                                                                <i class="ti ti-check-double"></i> {{ __('Complete') }}
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <li><hr class="dropdown-divider"></li>

                                                    <li>
                                                        <form action="{{ route('admin.applications.destroy', ['locale' => $locale, 'id' => $application->id]) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('{{ __('Вы уверены, что хотите удалить эту заявку?') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-trash"></i> {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-inbox icon-lg mb-2"></i>
                                                <p>{{ __('No applications found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($applications->hasPages())
                        <div class="mt-4">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
