@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('Blogs')}} 
                        <span class="badge bg-blue text-blue-fg">{{ $blogs->total() }}</span>
                    </h3>
                    <div class="card-actions">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.blogs.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i>
                                {{__('Add new')}}
                            </a>
                            <a href="{{ route('admin.blog-categories.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-info">
                                <i class="ti ti-category"></i>
                                {{__('Categories')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.blogs.index', ['locale' => app()->getLocale()]) }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="{{__('Search by title...')}}" 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.blogs.index', ['locale' => app()->getLocale()]) }}">
                                <div class="input-group">
                                    <select name="category" class="form-select">
                                        <option value="">{{__('All Categories')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="ti ti-filter"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($blogs as $blog)
                                    <tr>
                                        <td><img width="100" src="{{ asset($blog->image) }}" alt=""></td>
                                        <td>{{ $blog->translated_title }}</td>
                                        <td>
                                            @if($blog->category)
                                                <span class="badge bg-blue text-blue-fg">
                                                    {{ $blog->category->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary text-secondary-fg">
                                                    {{__('Without category')}}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($blog->status == 1)
                                               <span class="badge bg-lime text-lime-fg">{{__('Active')}}</span> 
                                            @else 
                                               <span class="badge bg-red text-red-fg">{{__('Inactive')}}</span> 
                                            @endif
                                        </td>
                                        <td>
                                            
                                            <a href="{{ route('admin.blogs.edit', ['locale' => app()->getLocale(), 'blog' => $blog->id]) }}"
                                                class="btn-sm btn-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.blogs.destroy', ['locale' => app()->getLocale(), 'blog' => $blog->id]) }}"
                                                class="text-red delete-item">
                                                <i class="ti ti-trash-x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{__('No Data Found!')}}</td>
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
