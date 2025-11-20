@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('Create Category')}}</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.course-categories.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <i class="ti ti-arrow-left"></i>
                            {{__('Back')}} 
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.course-categories.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <x-input-file-block name="image" />
                            </div>

                            
                            <div class="col-md-12">
                                <x-input-block name="name" placeholder="{{__('Enter category name')}}" />
                            </div>

                            <div class="col-md-3">
                                <x-input-toggle-block name="show_at_treading" label="{{__('Show at Trading')}}" />
                            </div>
                            <div class="col-md-3">
                                <x-input-toggle-block name="status" label="{{__('Status')}}" />
                            </div>

                           
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
@endsection
