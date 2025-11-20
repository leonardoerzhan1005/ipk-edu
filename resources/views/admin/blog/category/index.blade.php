@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Blog Categories</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.blog-categories.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i>
                            Add new
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{__('Name')}} ({{ app()->getLocale() }})</th>
                                    <th>{{__('Translations')}}</th>
                                    <th>{{__('Blogs Count')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <strong>{{ $category->name }}</strong>
                                                @if($category->slug)
                                                    <small class="text-muted ms-2">({{ $category->slug }})</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @foreach(['ru', 'kk', 'en'] as $lang)
                                                    @php
                                                        $translation = $category->translations->where('locale', $lang)->first();
                                                    @endphp
                                                    @if($translation)
                                                        <span class="badge bg-blue text-blue-fg" title="{{ $translation->name }}">
                                                            {{ strtoupper($lang) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary text-secondary-fg" title="No translation">
                                                            {{ strtoupper($lang) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-green text-green-fg">
                                                {{ $category->blogs_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($category->status == 1)
                                               <span class="badge bg-lime text-lime-fg">{{__('Active')}}</span> 
                                            @else 
                                               <span class="badge bg-red text-red-fg">{{__('Inactive')}}</span> 
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.blog-categories.edit', ['locale' => app()->getLocale(), 'blog_category' => $category->id]) }}"
                                                    class="btn btn-sm btn-primary" title="{{__('Edit')}}">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                
                                                <a href="{{ route('admin.blogs.index', ['locale' => app()->getLocale(), 'category' => $category->id]) }}"
                                                    class="btn btn-sm btn-info" title="{{__('View Blogs')}}">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                
                                                <a href="{{ route('admin.blog-categories.destroy', ['locale' => app()->getLocale(), 'blog_category' => $category->id]) }}"
                                                    class="btn btn-sm btn-danger delete-item" title="{{__('Delete')}}">
                                                    <i class="ti ti-trash-x"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Data Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
