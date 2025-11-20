@extends('admin.layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Course Applications</h3>
        <div class="card-actions">
          <form class="d-flex gap-2" method="GET">
            <input class="form-control" name="q" value="{{ $q }}" placeholder="Search name/email">
            <select name="status" class="form-select" onchange="this.form.submit()">
              <option value="">All</option>
              <option value="pending" @selected($status==='pending')>Pending</option>
              <option value="approved" @selected($status==='approved')>Approved</option>
              <option value="rejected" @selected($status==='rejected')>Rejected</option>
            </select>
            <button class="btn btn-primary" type="submit">Filter</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>Applicant</th>
                <th>Email</th>
                <th>Course</th>
                <th>Session</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($apps as $a)
              <tr>
                <td>
                  <a href="{{ route('admin.course-applications.show', ['locale'=>app()->getLocale(), 'application'=>$a->id]) }}">
                    {{ $a->full_name }}
                  </a>
                </td>
                <td>{{ $a->email }}</td>
                <td>{{ $a->course?->title }}</td>
                <td>
                  @if($a->session)
                    {{ $a->session->start_date->format('Y-m-d') }} - {{ $a->session->end_date->format('Y-m-d') }}
                  @else
                    —
                  @endif
                </td>
                <td><span class="badge @class(['bg-yellow'=> $a->status==='pending','bg-lime'=> $a->status==='approved','bg-red'=> $a->status==='rejected'])">{{ ucfirst($a->status) }}</span></td>
                <td>
                  <a href="{{ route('admin.course-applications.show', ['locale'=>app()->getLocale(), 'application'=>$a->id]) }}" class="btn btn-sm btn-outline-secondary">View</a>
                  <button type="button" class="btn btn-sm btn-success js-open-status" data-id="{{ $a->id }}" data-status="approved" data-name="{{ $a->full_name }}" data-course="{{ $a->course?->title }}" @disabled($a->status==='approved')>Approve</button>
                  <button type="button" class="btn btn-sm btn-danger js-open-status" data-id="{{ $a->id }}" data-status="rejected" data-name="{{ $a->full_name }}" data-course="{{ $a->course?->title }}" @disabled($a->status==='rejected')>Reject</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="mt-3">{{ $apps->links() }}</div>
      </div>
    </div>
  </div>
</div>

<!-- Confirm Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="statusForm" method="POST">
        @csrf
        <input type="hidden" name="status" id="statusInput" value="">
        <div class="modal-header">
          <h5 class="modal-title">Confirm action</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="statusText" class="mb-2"></div>
          <div class="small text-secondary" id="statusExtra"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="statusSubmit">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const locale = '{{ app()->getLocale() }}';
    const modalEl = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const input = document.getElementById('statusInput');
    const text = document.getElementById('statusText');
    const extra = document.getElementById('statusExtra');
    document.addEventListener('click', function(e){
      const btn = e.target.closest('.js-open-status');
      if(!btn) return;
      const id = btn.getAttribute('data-id');
      const status = btn.getAttribute('data-status');
      const name = btn.getAttribute('data-name') || '';
      const course = btn.getAttribute('data-course') || '';
      input.value = status;
      text.textContent = (status === 'approved') ? 'Approve this application?' : 'Reject this application?';
      extra.textContent = name && course ? (name + ' → ' + course) : '';
      form.setAttribute('action', `/admin/${locale}/course-applications/${id}/status`);
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    });
  })();
</script>
@endpush
@endsection


