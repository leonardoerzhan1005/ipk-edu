// Улучшенная конфигурация TinyMCE с расширенными функциями для работы с изображениями
document.addEventListener("DOMContentLoaded", function () {
    tinymce.init({
        selector: '.editor',
        height: 600,
        menubar: true,
        
        // Расширенные плагины для работы с изображениями и контентом
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
            'template', 'codesample', 'hr', 'pagebreak', 'nonbreaking', 'toc',
            'imagetools', 'textpattern', 'noneditable', 'quickbars', 'accordion',
            'autosave', 'save', 'directionality', 'code', 'codesample', 'media',
            'powerpaste', 'rtc', 'tinymcespellchecker', 'a11ychecker', 'linkchecker'
        ],
        
        // Расширенная панель инструментов
        toolbar: [
            'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat',
            'alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist checklist | pagebreak | charmap emoticons',
            'searchreplace | insertfile image media table | link anchor | ltr rtl | code codesample | fullscreen preview | save print | help'
        ],
        
        // Дополнительная панель инструментов
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        quickbars_insert_toolbar: 'quickimage quicktable',
        
        // Настройки для изображений
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
            {title: 'No border', value: 'img-no-border'},
            {title: 'Shadow', value: 'img-shadow'},
            {title: 'Border', value: 'img-border'}
        ],
        
        // Настройки для медиа
        media_live_embeds: true,
        media_url_resolver: function (data, resolve) {
            if (data.url.indexOf('youtube.com') !== -1 || data.url.indexOf('youtu.be') !== -1) {
                var embedHtml = '<iframe src="' + data.url + '" width="560" height="315" frameborder="0" allowfullscreen></iframe>';
                resolve({html: embedHtml});
            } else {
                resolve({html: ''});
            }
        },
        
        // Настройки для таблиц
        table_default_attributes: {
            border: '1'
        },
        table_default_styles: {
            'border-collapse': 'collapse',
            'width': '100%'
        },
        table_class_list: [
            {title: 'None', value: ''},
            {title: 'Table', value: 'table'},
            {title: 'Striped', value: 'table table-striped'},
            {title: 'Bordered', value: 'table table-bordered'},
            {title: 'Hover', value: 'table table-hover'},
            {title: 'Condensed', value: 'table table-condensed'}
        ],
        
        // Настройки для ссылок
        link_default_protocol: 'https',
        link_context_toolbar: true,
        link_title: true,
        link_target_list: [
            {title: 'None', value: ''},
            {title: 'Same window', value: '_self'},
            {title: 'New window', value: '_blank'}
        ],
        
        // Настройки для списков
        lists_indent_on_tab: true,
        
        // Настройки для шрифтов
        font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
        font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
        
        // Настройки для автосохранения
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_retention: '2m',
        
        // Настройки для проверки орфографии
        browser_spellcheck: true,
        contextmenu: 'link image imagetools table spellchecker configurepermanentpen',
        
        // Настройки для кода
        codesample_languages: [
            {text: 'HTML/XML', value: 'markup'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'CSS', value: 'css'},
            {text: 'PHP', value: 'php'},
            {text: 'Ruby', value: 'ruby'},
            {text: 'Python', value: 'python'},
            {text: 'Java', value: 'java'},
            {text: 'C', value: 'c'},
            {text: 'C#', value: 'csharp'},
            {text: 'C++', value: 'cpp'}
        ],
        
        // Настройки для вставки файлов
        file_picker_types: 'image',
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                // Создаем input для выбора файла
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                
                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function () {
                        callback(reader.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
                
                input.click();
            }
        },
        
        // Настройки для перетаскивания изображений
        paste_data_images: true,
        paste_enable_default_filters: false,
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles_if_webkit: false,
        paste_merge_formats: true,
        
        // Настройки для изменения размера
        resize: true,
        resize_img_proportional: true,
        
        // Настройки для полноэкранного режима
        fullscreen_native: true,
        
        // Настройки для предварительного просмотра
        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform color background-color',
        
        // Настройки для поиска и замены
        searchreplace_replace_dialog: true,
        
        // Настройки для вставки даты и времени
        insertdatetime_formats: ['%Y-%m-%d', '%H:%M:%S', '%Y-%m-%d %H:%M:%S'],
        insertdatetime_element: true,
        
        // Настройки для направления текста
        directionality: 'ltr',
        
        // Настройки для проверки доступности
        a11y_advanced_options: true,
        
        // Настройки для проверки ссылок
        linkchecker_check_on_startup: true,
        linkchecker_status_codes: '200,301,302',
        
        // Настройки для PowerPaste
        powerpaste_word_import: 'prompt',
        powerpaste_html_import: 'prompt',
        powerpaste_allow_local_images: true,
        
        // Настройки для автоматического сохранения
        save_onsavecallback: function () {
            console.log('Content saved');
        },
        
        // Настройки для печати
        print: {
            prepend: '<h1>Document Title</h1>',
            header: 'Document Header',
            footer: 'Document Footer'
        },
        
        // Стили контента
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
            table { 
                border-collapse: collapse; 
                width: 100%; 
                margin: 20px 0;
            }
            table th, table td { 
                border: 1px solid #ddd; 
                padding: 12px; 
                text-align: left;
            }
            table th { 
                background-color: #f5f5f5; 
                font-weight: bold;
            }
            blockquote { 
                border-left: 4px solid #007cba; 
                margin: 20px 0; 
                padding: 10px 20px; 
                background-color: #f9f9f9;
                font-style: italic;
            }
            code { 
                background-color: #f4f4f4; 
                padding: 2px 4px; 
                border-radius: 3px; 
                font-family: 'Courier New', monospace;
            }
            pre { 
                background-color: #f4f4f4; 
                padding: 15px; 
                border-radius: 5px; 
                overflow-x: auto;
                border: 1px solid #ddd;
            }
            h1, h2, h3, h4, h5, h6 { 
                color: #2c3e50; 
                margin-top: 30px; 
                margin-bottom: 15px;
            }
            .img-responsive { 
                max-width: 100%; 
                height: auto; 
            }
            .img-rounded { 
                border-radius: 6px; 
            }
            .img-circle { 
                border-radius: 50%; 
            }
            .img-thumbnail { 
                padding: 4px; 
                border: 1px solid #ddd; 
                border-radius: 4px; 
            }
            .img-no-border { 
                border: none; 
            }
            .img-shadow { 
                box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
            }
            .img-border { 
                border: 2px solid #333; 
            }
        `,
        
        // Настройки для мобильных устройств
        mobile: {
            theme: 'mobile',
            plugins: ['autosave', 'lists', 'autolink'],
            toolbar: ['undo', 'bold', 'italic', 'styleselect']
        },
        
        // Настройки для темной темы
        skin: 'oxide',
        content_css: 'default',
        
        // Настройки для отладки
        debug: false,
        
        // Настройки для производительности
        cache_suffix: '?v=1.0.0',
        
        // Настройки для локализации
        language: 'ru',
        
        // Настройки для событий
        setup: function (editor) {
            // Обработчик для изменения размера изображений
            editor.on('ObjectResized', function (e) {
                console.log('Image resized:', e.target);
            });
            
            // Обработчик для вставки изображений
            editor.on('BeforeSetContent', function (e) {
                if (e.content.includes('<img')) {
                    console.log('Image content detected');
                }
            });
            
            // Обработчик для сохранения
            editor.on('SaveContent', function (e) {
                console.log('Content saved');
            });
            
            // Добавляем кнопку для вставки изображения с перетаскиванием
            editor.ui.registry.addButton('dragimage', {
                text: 'Drag Image',
                onAction: function () {
                    editor.windowManager.open({
                        title: 'Insert Image by Drag & Drop',
                        body: {
                            type: 'panel',
                            items: [
                                {
                                    type: 'htmlpanel',
                                    html: '<div id="dropzone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; background: #f9f9f9;">Drop images here or click to select</div>'
                                }
                            ]
                        },
                        onAction: function (dialog) {
                            dialog.close();
                        }
                    });
                }
            });
        }
    });
});
