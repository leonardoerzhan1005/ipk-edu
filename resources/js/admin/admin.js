import { data } from 'autoprefixer';
import $ from 'jquery';
import './image-handler.js';

window.$ = window.jQuery = $;
/** Notyf init */
var notyf = new Notyf({
    duration: 8000,
    dismissible: true
});




const csrf_token = $(`meta[name="csrf_token"]`).attr('content');
const base_url = $(`meta[name="base_url"]`).attr('content');

document.addEventListener("DOMContentLoaded", function () {
    var el;
    window.TomSelect && (new TomSelect(el = document.getElementById('select-users'), {
        copyClassesToDropdown: false,
        dropdownParent: 'body',
        controlInput: '<input>',
        render:{
            item: function(data,escape) {
                if( data.customProperties ){
                    return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                }
                return '<div>' + escape(data.text) + '</div>';
            },
            option: function(data,escape){
                if( data.customProperties ){
                    return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                }
                return '<div>' + escape(data.text) + '</div>';
            },
        },
    }));
});

document.addEventListener("DOMContentLoaded", function () {
    tinymce.init({
        selector: '.editor',
        height: 600,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
            'imagetools', 'textpattern', 'quickbars', 'autosave', 'save'
        ],
        toolbar: [
            'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat',
            'alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | pagebreak | charmap emoticons',
            'searchreplace | image media table | link anchor | code | fullscreen preview | save | help'
        ],
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        quickbars_insert_toolbar: 'quickimage quicktable',
        image_advtab: true,
        image_caption: true,
        image_title: true,
        image_description: true,
        image_dimensions: true,
        image_class_list: [
            {title: 'Responsive', value: 'img-responsive'},
            {title: 'Rounded', value: 'img-rounded'},
            {title: 'Circle', value: 'img-circle'},
            {title: 'Thumbnail', value: 'img-thumbnail'},
            {title: 'Shadow', value: 'img-shadow'}
        ],
        
        // Настройки для загрузки изображений через Laravel FileManager
        file_picker_types: 'image',
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                // Используем Laravel FileManager для выбора/загрузки изображений
                var lfmRoute = '/admin/laravel-filemanager?type=Images';
                
                // Открываем окно Laravel FileManager
                window.open(lfmRoute, 'FileManager', 
                    'width=900,height=600,scrollbars=yes,resizable=yes');
                
                // Функция обратного вызова для получения выбранного файла
                window.SetUrl = function (items) {
                    var file_path = items.map(function (item) {
                        return item.url;
                    }).join('');
                    
                    // Вставляем изображение в редактор
                    callback(file_path, {
                        alt: '',
                        title: ''
                    });
                };
            }
        },
        
        // Обработчик загрузки изображений на сервер (для drag & drop и вставки из буфера)
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                // Получаем текущую локаль из URL
                var locale = window.location.pathname.split('/')[1] || 'ru';
                xhr.open('POST', '/' + locale + '/admin/upload-editor-image');
                
                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };
                
                xhr.onload = function() {
                    if (xhr.status === 403) {
                        reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                        return;
                    }
                    
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    
                    var json = JSON.parse(xhr.responseText);
                    
                    if (!json || typeof json.location != 'string') {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    
                    resolve(json.location);
                };
                
                xhr.onerror = function () {
                    reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };
                
                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', csrf_token);
                
                xhr.send(formData);
            });
        },
        
        // Автоматическая загрузка изображений
        automatic_uploads: true,
        
        // Разрешить вставку изображений из буфера обмена
        paste_data_images: true,
        
        resize: true,
        resize_img_proportional: true,
        content_style: `
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                font-size: 16px; 
                line-height: 1.6; 
                color: #333;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            img { 
                max-width: 100%; 
                height: auto; 
                border-radius: 4px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
        `,
        setup: function (editor) {
            editor.on('ObjectResized', function (e) {
                console.log('Image resized:', e.target);
            });
            
            // Обработчик для вставки изображений
            editor.on('BeforeSetContent', function (e) {
                if (e.content.includes('<img')) {
                    console.log('Image content detected');
                }
            });
        }
    });
});

var delete_url = null;

$(function() {
    $('.select2').select2();
});

/** Delete Item with confirmation */

$('.delete-item').on('click', function(e) {
    e.preventDefault();

    let url = $(this).attr('href');
    delete_url = url;

    $('#modal-danger').modal("show");
});

$('.delete-confirm').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        method: 'DELETE',
        url: delete_url,
        data: {
            _token: csrf_token
        },
        beforeSend: function() {
            $('.delete-confirm').text("Deleting...");
        },
        success: function(data) {
            window.location.reload();
        },
        error: function(xhr, status, error) {
            let errorMessage = xhr.responseJSON;
            notyf.error(errorMessage.message);
        },
        complete: function() {

            $('.delete-confirm').text("Delete");
        }
    })
});


/** Database Clear with confirmation */

$('.db-clear').on('click', function(e) {
    e.preventDefault();

    let url = $(this).attr('href');
    delete_url = url;

    $('#modal-database-clear').modal("show");
});

$('.db-clear-submit').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        method: 'DELETE',
        url: base_url + '/admin/database-clear',
        data: {
            _token: csrf_token
        },
        beforeSend: function() {
            $('.db-clear-btn').text("Wiping...");
        },
        success: function(data) {
            window.location.reload();
        },
        error: function(xhr, status, error) {
            let errorMessage = xhr.responseJSON;
            notyf.error(errorMessage.message);
        },
        complete: function() {

            $('.db-clear-btn').text("Delete");
        }
    })
});


/** Certificate js */

$(function() {
    $('.draggable-element').draggable({
        containment: '.certificate-body',
        stop: function(event, ui) {
            var elementId = $(this).attr('id');
            var xPosition = ui.position.left;
            var yPosition = ui.position.top;

            $.ajax({
                method: 'POST',
                url: `${base_url}/admin/certificate-item`,
                data: {
                    '_token': csrf_token,
                    'element_id': elementId,
                    'x_position': xPosition,
                    'y_position': yPosition
                },
                success: function(data) {},
                error: function(xhr, status, error) {
                }

            })
        }
    });
})


/** Featured Instructor js */
$(function() {
    $('.select_instructor').on('change', function() {
        let id = $(this).val();

        $.ajax({
            method: 'get',
            url: `${base_url}/admin/get-instructor-courses/${id}`,
            beforeSend: function() {
                $('.instructor_courses').empty();
            },
            success: function(data) {
                $.each(data.courses, function(key, value) {
                    
                        let option = `<option value="${value.id}">${value.title}</option>`;
                    $('.instructor_courses').append(option);
                })
            },
            error: function(xhr, status, error) {
                notyf.error(data.error);
            }
        })
    });
});



