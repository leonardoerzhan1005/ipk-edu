<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Chapter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ $action }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ request()->route('course') ?? ($id ?? '') }}">
            <div class="form-group mb-3">
                <label>Title</label>
                <input type="text" class="form-control" name="title" value="{{ $chapter->title ?? '' }}" required>
            </div>
            <div class="form-group text-end">
                <button type="submit" class="btn btn-primary">{{ !empty($editMode) ? 'Update' : 'Create' }}</button>
            </div>
        </form>
    </div>
</div>


