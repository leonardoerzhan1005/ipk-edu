<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Lesson</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ !empty($editMode) ? route('admin.course-contents-v2.lessons.update', ['locale' => app()->getLocale(), 'id' => $lesson->id ?? 0]) : route('admin.course-contents-v2.lessons.store', ['locale' => app()->getLocale()]) }}" method="POST">
            @csrf
            @if(!empty($editMode)) @method('POST') @endif
            <input type="hidden" name="course_id" value="{{ $courseId }}">
            <input type="hidden" name="chapter_id" value="{{ $chapterId }}">
            <div class="form-group mb-3">
                <label>Title</label>
                <input type="text" class="form-control" name="title" value="{{ $lesson->title ?? '' }}" required>
            </div>
            <div class="form-group mb-3">
                <label>Source</label>
                <select name="source" class="form-control" required>
                    <option value="upload" {{ (isset($lesson) && $lesson->storage=='upload') ? 'selected' : '' }}>Upload</option>
                    <option value="external_link" {{ (isset($lesson) && $lesson->storage=='external_link') ? 'selected' : '' }}>External</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label>File Type</label>
                <select name="file_type" class="form-control" required>
                    @foreach(['video','audio','file','pdf','doc'] as $t)
                        <option value="{{ $t }}" {{ (isset($lesson) && $lesson->file_type==$t) ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label>Duration</label>
                <input type="text" class="form-control" name="duration" value="{{ $lesson->duration ?? '' }}" required>
            </div>
            <div class="form-group mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required>{{ $lesson->description ?? '' }}</textarea>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_preview" value="1" {{ !empty($lesson) && $lesson->is_preview ? 'checked' : '' }}>
                <label class="form-check-label">Preview</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="downloadable" value="1" {{ !empty($lesson) && $lesson->downloadable ? 'checked' : '' }}>
                <label class="form-check-label">Downloadable</label>
            </div>
            <div class="form-group text-end">
                <button type="submit" class="btn btn-primary">{{ !empty($editMode) ? 'Update' : 'Create' }}</button>
            </div>
        </form>
    </div>
</div>


