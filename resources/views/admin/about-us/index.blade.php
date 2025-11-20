@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('About Us Sections')}}
                        <span class="badge bg-blue text-blue-fg">{{ $items->total() }}</span>
                    </h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.about-us.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i>
                            {{__('Add New Section')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{__('Order')}}</th>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Languages')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td><strong>{{ $item->order }}</strong></td>
                                        <td>
                                            @if($item->image)
                                                <img width="80" src="{{ asset($item->image) }}" alt="">
                                            @else
                                                <span class="text-muted">{{__('No image')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->translated_title }}</strong>
                                            @if($item->translated_subtitle)
                                                <br><small class="text-muted">{{ $item->translated_subtitle }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($item->translations as $translation)
                                                <span class="badge bg-info text-info-fg me-1">
                                                    {{ strtoupper($translation->locale) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($item->status == 1)
                                               <span class="badge bg-lime text-lime-fg">{{__('Active')}}</span>
                                            @else
                                               <span class="badge bg-red text-red-fg">{{__('Inactive')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.about-us.edit', ['locale' => app()->getLocale(), 'about_us' => $item->id]) }}"
                                                class="btn-sm btn-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <a href="{{ route('admin.about-us.destroy', ['locale' => app()->getLocale(), 'about_us' => $item->id]) }}"
                                                class="text-red delete-item">
                                                <i class="ti ti-trash-x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{__('No Data Found!')}}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
