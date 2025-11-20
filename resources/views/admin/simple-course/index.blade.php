@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Управление курсами (упрощенно)</h3>
        <div class="card-actions">
          <a href="{{ route('admin.simple-courses.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>
            Создать курс
          </a>
        </div>
      </div>

      <!-- Filters -->
      <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Поиск по названию..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3">
            <select name="category" class="form-select">
              <option value="">Все категории</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ti ti-search me-1"></i>
              Поиск
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.simple-courses.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary w-100">
              <i class="ti ti-x me-1"></i>
              Сбросить
            </a>
          </div>
        </form>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th style="width: 80px">Изображение</th>
                <th>Название</th>
                <th>Инструктор</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Длительность</th>
                <th>Статус</th>
                <th>Одобрен</th>
                <th class="w-1">Действия</th>
              </tr>
            </thead>
            <tbody>
              @forelse($courses as $course)
              <tr>
                <td>{{ $course->id }}</td>
                <td>
                  <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}" class="img-thumbnail" style="max-width: 60px;">
                </td>
                <td>
                  <div>{{ $course->translated_title ?? $course->title }}</div>
                  <small class="text-muted">{{ \Str::limit($course->slug, 40) }}</small>
                </td>
                <td>{{ $course->instructor?->name ?? 'N/A' }}</td>
                <td>{{ $course->category?->name ?? 'N/A' }}</td>
                <td>
                  @if($course->discount > 0)
                    <span class="text-decoration-line-through text-muted">${{ $course->price }}</span>
                    <span class="text-success fw-bold">${{ $course->discount }}</span>
                  @else
                    ${{ $course->price }}
                  @endif
                </td>
                <td>{{ $course->duration }} ч.</td>
                <td>
                  @if($course->status == 'active')
                    <span class="badge bg-success">Активный</span>
                  @elseif($course->status == 'inactive')
                    <span class="badge bg-secondary">Неактивный</span>
                  @else
                    <span class="badge bg-warning">Ожидает</span>
                  @endif
                </td>
                <td>
                  <form action="{{ route('admin.simple-courses.toggle-approval', ['locale' => app()->getLocale(), 'id' => $course->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $course->is_approved ? 'btn-success' : 'btn-outline-secondary' }}" title="{{ $course->is_approved ? 'Одобрен' : 'Не одобрен' }}">
                      <i class="ti ti-{{ $course->is_approved ? 'check' : 'x' }}"></i>
                    </button>
                  </form>
                </td>
                <td>
                  <div class="btn-group">
                    <a href="{{ route('admin.simple-courses.edit', ['locale' => app()->getLocale(), 'id' => $course->id]) }}" class="btn btn-sm btn-primary">
                      <i class="ti ti-edit"></i>
                    </a>
                    <form action="{{ route('admin.simple-courses.destroy', ['locale' => app()->getLocale(), 'id' => $course->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить этот курс?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="10" class="text-center py-4">
                  <div class="text-muted">
                    <i class="ti ti-inbox fs-1"></i>
                    <p class="mt-2">Курсы не найдены</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($courses->hasPages())
      <div class="card-footer">
        {{ $courses->links() }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
