/** const variables */
import $ from 'jquery';
window.$ = window.jQuery = $;

const csrf_token = $(`meta[name="csrf_token"]`).attr('content');
const base_url = $(`meta[name="base_url"]`).attr('content');

// Derive admin locale from current path: /admin/{locale}/...
const pathParts = window.location.pathname.split('/').filter(Boolean);
const adminIndex = pathParts.indexOf('admin');
const adminLocale = adminIndex > -1 && pathParts.length > adminIndex + 1 ? pathParts[adminIndex + 1] : '';
const adminBase = adminLocale ? `${base_url}/admin/${adminLocale}` : `${base_url}/admin`;
// Frontend API lives under /{locale}/api, not under /admin
const localeBase = adminLocale ? `${base_url}/${adminLocale}` : base_url;

const basic_info_url = `${adminBase}/courses/create`;
const update_url = `${adminBase}/courses/update`;

var notyf = new Notyf({
    duration: 5000,
    dismissible: true
});

var loader = `
<div class="modal-content text-center p-3" style="display:inline">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
`;

/** reusable functions */

function updateApproveStatus(id, status) {
    console.log("working");

    $.ajax({
        method: 'PUT',
        url: `${adminBase}/courses/${id}/update-approval`,
        data: {
            _token: csrf_token,
            status: status
        },
        success: function (data) {
            window.location.reload();
        },
        error: function (xhr, status, error) {

        }

    })
}

/** on dom load */
$(function () {
    // Populate faculties and specialties on More Info step
    const $facultySelect = $("select[name='faculty_id']");
    const $specialtySelect = $("select[name='specialty_id']");
    if ($facultySelect.length && $specialtySelect.length) {
        // Initialize select2 if available
        if (typeof $.fn.select2 === 'function') {
            $('.select2').select2();
        }

        // Load faculties
        $.ajax({
            method: 'GET',
            url: `${localeBase}/api/faculties`,
            success: function (items) {
                $facultySelect.find('option:not(:first)').remove();
                (items || []).forEach(function (i) {
                    const selected = $facultySelect.data('selected') == i.id ? 'selected' : '';
                    $facultySelect.append(`<option value="${i.id}" ${selected}>${i.name}</option>`);
                });
                $facultySelect.trigger('change.select2');
            }
        });

        function loadSpecialties(facultyId) {
            $specialtySelect.find('option:not(:first)').remove();
            if (!facultyId) { $specialtySelect.trigger('change.select2'); return; }
            $.ajax({
                method: 'GET',
                url: `${localeBase}/api/specialties`,
                data: { faculty_id: facultyId },
                success: function (items) {
                    (items || []).forEach(function (i) {
                        const selected = $specialtySelect.data('selected') == i.id ? 'selected' : '';
                        $specialtySelect.append(`<option value="${i.id}" ${selected}>${i.name}</option>`);
                    });
                    $specialtySelect.trigger('change.select2');
                }
            });
        }

        // initial load (if course has preselected values)
        const preFaculty = $facultySelect.data('selected') || $facultySelect.val();
        if (preFaculty) { loadSpecialties(preFaculty); }

        // on change
        $facultySelect.on('change', function () {
            loadSpecialties($(this).val());
        });
    }
    /** change course approval status */
    $('.update-approval-status').on('change', function () {
        let id = $(this).data('id');
        let status = $(this).val();

        updateApproveStatus(id, status);
    })


    //course tab navigation
    $('.course-tab').on('click', function (e) {
        e.preventDefault();
        let step = $(this).data('step');
        $('.course-form').find('input[name=next_step]').val(step);
        $('.course-form').trigger('submit');
    });

    $('.basic_info_form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            method: "POST",
            url: basic_info_url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status == 'success') {

                    window.location.href = data.redirect
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    notyf.error(value[0]);
                })

            },
            complete: function () { }
        })

    });

    $('.basic_info_update_form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            method: "POST",
            url: update_url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status == 'success') {
                    window.location.href = data.redirect
                }
            },
            error: function (xhr, status, error) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    notyf.error(value[0]);
                })
            },
            complete: function () { }
        })

    });

    $('.more_info_form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            method: "POST",
            url: update_url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status == 'success') {

                    window.location.href = data.redirect
                }
            },
            error: function (xhr, status, error) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    notyf.error(value[0]);
                })
            },
            complete: function () { }
        })

    });

    // show hide path input depending on source
    $(document).on('change', '.storage', function () {
        let value = $(this).val();
        $('.source_input').val('');
        console.log("working");
        if (value == 'upload') {
            $('.upload_source').removeClass('d-none');
            $('.external_source').addClass('d-none');
        } else {
            $('.upload_source').addClass('d-none');
            $('.external_source').removeClass('d-none');
        }
    });

    /** Course Contents */

    $('.dynamic-modal-btn').on('click', function (e) {
        e.preventDefault();
        $('#dynamic-modal').modal("show");

        let course_id = $(this).data('id');
        let url = $(this).data('url') || `${adminBase}/course-content/${course_id}/create-chapter`;

        $.ajax({
            method: 'GET',
            url: url,
            data: {},
            beforeSend: function () {
                $('.dynamic-modal-content').html(loader);
            },
            success: function (data) {
                $('.dynamic-modal-content').html(data);
            },
            error: function (xhr, status, error) {

            }
        })
    });

    $('.edit_chapter').on('click', function (e) {
        e.preventDefault();
        $('#dynamic-modal').modal("show");

        let chapter_id = $(this).data('chapter-id');

        $.ajax({
            method: 'GET',
            url: `${adminBase}/course-content/${chapter_id}/edit-chapter`,
            data: {},
            beforeSend: function () {
                $('.dynamic-modal-content').html(loader);
            },
            success: function (data) {
                $('.dynamic-modal-content').html(data);
            },
            error: function (xhr, status, error) {

            }
        })
    });



    $('.add_lesson').on('click', function () {

        $('#dynamic-modal').modal("show");

        let courseId = $(this).data('course-id');
        let chapterId = $(this).data('chapter-id');
        $.ajax({
            method: 'GET',
            url: `${adminBase}/course-content/create-lesson`,
            data: {
                'course_id': courseId,
                'chapter_id': chapterId
            },
            beforeSend: function () {
                $('.dynamic-modal-content').html(loader);
            },
            success: function (data) {
                $('.dynamic-modal-content').html(data);
            },
            error: function (xhr, status, error) {

            }
        })
    });

    $('.edit_lesson').on('click', function () {

        $('#dynamic-modal').modal("show");

        let courseId = $(this).data('course-id');
        let chapterId = $(this).data('chapter-id');
        let lessonId = $(this).data('lesson-id');

        $.ajax({
            method: 'GET',
            url: `${adminBase}/course-content/edit-lesson`,
            data: {
                'course_id': courseId,
                'chapter_id': chapterId,
                'lesson_id': lessonId
            },
            beforeSend: function () {
                $('.dynamic-modal-content').html(loader);
            },
            success: function (data) {
                $('.dynamic-modal-content').html(data);
            },
            error: function (xhr, status, error) {

            }
        })
    });

    if ($('.sortable_list li').length) {
        $('.sortable_list').sortable({
            items: "li",
            containment: "parent",
            cursor: "move",
            handle: ".dragger",
            update: function (event, ui) {
                let orderIds = $(this).sortable("toArray", {
                    attribute: "data-lesson-id",
                });

                let chapterId = ui.item.data("chapter-id");

                $.ajax({
                    method: 'POST',
                    url: `${adminBase}/course-chapter/${chapterId}/sort-lesson`,
                    data: {
                        _token: csrf_token,
                        order_ids: orderIds
                    },
                    success: function (data) {
                        notyf.success(data.message);
                    },
                    error: function (xhr, status, error) {
                        notyf.error(data.error);
                    }
                })

            }
        });
    }

    $('.sort_chapter_btn').on('click', function () {
        $('#dynamic-modal').modal("show");
        let courseId = $(this).data('id');
        $.ajax({
            method: 'GET',
            url: `${adminBase}/course-content/${courseId}/sort-chapter`,
            data: {
            },
            beforeSend: function () {
                $('.dynamic-modal-content').html(loader);
            },
            success: function (data) {
                $('.dynamic-modal-content').html(data);
            },
            error: function (xhr, status, error) {
                notyf.error(error);
            }
        })
    });


});
