@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Application Courses Settings</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Available</th>
                                    <th>Faculty</th>
                                    <th>Specialization</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $course)
                                    <tr>
                                        <td>{{ $course->id }}</td>
                                        <td>{{ $course->title }}</td>
                                        <td>
                                            @if($course->is_available_for_applications)
                                                <span class="status status-green">Yes</span>
                                            @else
                                                <span class="status status-red">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $course->faculty?->name }}</td>
                                        <td>{{ $course->specialty?->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.application-courses.edit', ['locale' => app()->getLocale(), 'course' => $course->id]) }}" class="btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Data Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


