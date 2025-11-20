@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Services</h4>
        <a href="{{ route('admin.services.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">Create</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->translated_title ?? '-' }}</td>
                        <td>{{ $service->translated_subtitle ?? '-' }}</td>
                        <td>{{ $service->display_order }}</td>
                        <td>
                            <span class="badge bg-{{ $service->status ? 'success' : 'secondary' }}">{{ $service->status ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.services.edit', ['locale' => app()->getLocale(), 'service' => $service->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.services.destroy', ['locale' => app()->getLocale(), 'service' => $service->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No services found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection


