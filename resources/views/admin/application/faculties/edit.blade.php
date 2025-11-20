@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('Update Faculty')}}</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.application-faculties.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                           {{__('Back')}} 
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.application-faculties.update', ['locale' => app()->getLocale(), 'application_faculty' => $faculty->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{ $faculty->slug }}">
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>
                        @php($trans = $faculty->translations->keyBy('locale'))
                        @foreach(['ru'=>'Русский','kk'=>'Қазақша','en'=>'English'] as $loc => $label)
                        <div class="mb-3">
                            <label class="form-label">{{__('Name')}} ({{ $label }}){{ $loc==='ru' ? ' *' : '' }}</label>
                            <input type="text" class="form-control" name="translations[{{ $loc }}][name]" placeholder="Enter name ({{ $label }})" value="{{ $trans[$loc]->name ?? '' }}">
                            <x-input-error :messages="$errors->get('translations.'.$loc.'.name')" class="mt-2" />
                        </div>
                        @endforeach
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                                {{__('Update')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


