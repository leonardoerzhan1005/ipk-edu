<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Sort Chapters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <ul class="item_list sortable_list">
            @foreach($chapters as $chapter)
            <li class="" data-lesson-id="{{ $chapter->id }}" data-chapter-id="{{ $chapter->course_id }}">
                <span>{{ $chapter->title }}</span>
                <div class="add_course_content_action_btn">
                    <a class="arrow dragger" href="javascript:;"><i class="ti ti-arrows-maximize"></i></a>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>


