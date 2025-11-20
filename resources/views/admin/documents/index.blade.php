@extends('admin.layouts.master')

@section('content')
<div class="container-xl">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">{{__('Documents')}}</h3>
      <div class="d-flex gap-2">
        <form method="GET" action="{{ route('admin.documents.index', ['locale' => request()->route('locale')]) }}" class="d-flex gap-2">
          <select name="category" class="form-select" onchange="this.form.submit()">
            <option value="">{{__('All categories')}}</option>
            @foreach(['normative' => __('Normative'), 'orders' => __('Orders'), 'manuals' => __('Manuals'), 'templates' => __('Templates')] as $key => $label)
              <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
            @endforeach
          </select>
        </form>
        <a href="{{ route('admin.documents.create', ['locale' => request()->route('locale')]) }}" class="btn btn-primary">{{__('Add')}}</a>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-vcenter">
        <thead>
          <tr>
            <th>{{__('Title')}}</th>
            <th>{{__('Category')}}</th>
            <th>{{__('Published')}}</th>
            <th>{{__('Status')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($documents as $doc)
          <tr>
            <td>{{ $doc->title }}</td>
            <td>{{ ucfirst($doc->category) }}</td>
            <td>{{ optional($doc->published_at)->format('Y-m-d') }}</td>
            <td>
              <span class="badge {{ $doc->status ? 'bg-green' : 'bg-secondary' }}">{{ $doc->status ? __('Active') : __('Inactive') }}</span>
            </td>
            <td class="w-1">
              <a href="{{ route('admin.documents.edit', ['locale' => request()->route('locale'), 'document' => $doc->id]) }}" class="btn btn-sm">{{__('Edit')}}</a>
              <form action="{{ route('admin.documents.destroy', ['locale' => request()->route('locale'), 'document' => $doc->id]) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('{{__('Delete?')}}')">{{__('Delete')}}</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted">{{__('No data')}}  </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $documents->links() }}</div>
  </div>
 </div>
@endsection


