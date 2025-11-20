@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Расписание курсов</h3>
        <div class="card-actions">
          <form class="d-flex gap-2" method="GET">
            <select class="form-select" name="course_id" onchange="this.form.submit()">
              <option value="">Все курсы</option>
              @foreach($courses as $c)
                <option value="{{ $c->id }}" @selected($courseId==$c->id)>{{ $c->translated_title ?? $c->title }}</option>
              @endforeach
            </select>
            <a href="{{ route('admin.course-sessions.create', ['locale'=>app()->getLocale(), 'course_id'=>$courseId]) }}" class="btn btn-primary">Добавить сессию</a>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>Порядок</th>
                <th>Курс</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
                <th>Формат</th>
                <th>Описание</th>
                <th>Активна</th>
                <th>Действия</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sessions as $s)
              <tr>
                <td>{{ $s->order }}</td>
                <td>{{ $s->course?->translated_title ?? $s->course?->title }}</td>
                <td>{{ $s->start_date->format('d.m.Y') }}</td>
                <td>{{ $s->end_date->format('d.m.Y') }}</td>
                <td>
                  @if($s->format == 'online')
                    <span class="badge bg-blue">Онлайн</span>
                  @elseif($s->format == 'offline')
                    <span class="badge bg-purple">Оффлайн</span>
                  @else
                    <span class="badge bg-indigo">Гибрид</span>
                  @endif
                </td>
                <td>
                  @if($s->translated_description)
                    <small>{{ \Str::limit($s->translated_description, 50) }}</small>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @if($s->is_active)
                    <span class="badge bg-lime">Да</span>
                  @else
                    <span class="badge bg-secondary">Нет</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.course-sessions.edit', ['locale'=>app()->getLocale(), 'course_session'=>$s->id]) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                  <form action="{{ route('admin.course-sessions.destroy', ['locale'=>app()->getLocale(), 'course_session'=>$s->id]) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить?')">Удалить</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="8" class="text-center">Нет данных</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">{{ $sessions->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
