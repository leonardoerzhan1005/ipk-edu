// Дополнительные функции для работы с изображениями в TinyMCE

// Функция для обработки перетаскивания изображений
function setupImageDragAndDrop() {
    const editors = document.querySelectorAll('.editor');
    
    editors.forEach(editor => {
        const textarea = editor;
        
        // Создаем зону для перетаскивания
        const dropZone = document.createElement('div');
        dropZone.className = 'image-drop-zone';
        dropZone.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 123, 255, 0.1);
            border: 2px dashed #007bff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #007bff;
            z-index: 1000;
            pointer-events: none;
        `;
        dropZone.textContent = 'Перетащите изображения сюда';
        
        // Добавляем зону к родительскому элементу
        const parent = textarea.parentElement;
        parent.style.position = 'relative';
        parent.appendChild(dropZone);
        
        // Обработчики событий перетаскивания
        textarea.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.style.display = 'flex';
        });
        
        textarea.addEventListener('dragleave', function(e) {
            if (!textarea.contains(e.relatedTarget)) {
                dropZone.style.display = 'none';
            }
        });
        
        textarea.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.style.display = 'none';
            
            const files = e.dataTransfer.files;
            handleImageFiles(files, textarea);
        });
        
        // Обработчик клика для выбора файлов
        textarea.addEventListener('click', function(e) {
            if (e.target === textarea) {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.multiple = true;
                
                input.onchange = function() {
                    handleImageFiles(input.files, textarea);
                };
                
                input.click();
            }
        });
    });
}

// Функция для обработки выбранных файлов изображений
function handleImageFiles(files, textarea) {
    Array.from(files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Создаем элемент изображения
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = `
                    max-width: 100%;
                    height: auto;
                    border-radius: 4px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    margin: 10px 0;
                    cursor: move;
                `;
                
                // Добавляем атрибуты для редактирования
                img.setAttribute('contenteditable', 'false');
                img.setAttribute('data-mce-object', 'img');
                
                // Добавляем обработчики для изменения размера
                img.addEventListener('mousedown', function(e) {
                    if (e.target === img) {
                        startImageResize(img, e);
                    }
                });
                
                // Вставляем изображение в textarea
                insertImageToTextarea(img, textarea);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Функция для вставки изображения в textarea
function insertImageToTextarea(img, textarea) {
    const imgHtml = img.outerHTML;
    const currentValue = textarea.value || '';
    const cursorPos = textarea.selectionStart || currentValue.length;
    
    const newValue = currentValue.slice(0, cursorPos) + imgHtml + currentValue.slice(cursorPos);
    textarea.value = newValue;
    
    // Обновляем TinyMCE если он инициализирован
    if (window.tinymce) {
        const editor = window.tinymce.get(textarea.id);
        if (editor) {
            editor.setContent(newValue);
        }
    }
}

// Функция для изменения размера изображения
function startImageResize(img, e) {
    e.preventDefault();
    
    const startX = e.clientX;
    const startY = e.clientY;
    const startWidth = img.offsetWidth;
    const startHeight = img.offsetHeight;
    
    // Создаем индикаторы изменения размера
    const resizeHandles = createResizeHandles(img);
    
    function handleMouseMove(e) {
        const deltaX = e.clientX - startX;
        const deltaY = e.clientY - startY;
        
        const newWidth = Math.max(50, startWidth + deltaX);
        const newHeight = Math.max(50, startHeight + deltaY);
        
        img.style.width = newWidth + 'px';
        img.style.height = newHeight + 'px';
        
        updateResizeHandles(resizeHandles, img);
    }
    
    function handleMouseUp() {
        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mouseup', handleMouseUp);
        removeResizeHandles(resizeHandles);
    }
    
    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('mouseup', handleMouseUp);
}

// Функция для создания индикаторов изменения размера
function createResizeHandles(img) {
    const handles = [];
    const handleSize = 8;
    
    // Создаем 8 индикаторов (углы и стороны)
    const positions = [
        {top: -handleSize/2, left: -handleSize/2, cursor: 'nw-resize'},
        {top: -handleSize/2, left: '50%', marginLeft: -handleSize/2, cursor: 'n-resize'},
        {top: -handleSize/2, right: -handleSize/2, cursor: 'ne-resize'},
        {top: '50%', right: -handleSize/2, marginTop: -handleSize/2, cursor: 'e-resize'},
        {bottom: -handleSize/2, right: -handleSize/2, cursor: 'se-resize'},
        {bottom: -handleSize/2, left: '50%', marginLeft: -handleSize/2, cursor: 's-resize'},
        {bottom: -handleSize/2, left: -handleSize/2, cursor: 'sw-resize'},
        {top: '50%', left: -handleSize/2, marginTop: -handleSize/2, cursor: 'w-resize'}
    ];
    
    positions.forEach((pos, index) => {
        const handle = document.createElement('div');
        handle.className = 'resize-handle';
        handle.style.cssText = `
            position: absolute;
            width: ${handleSize}px;
            height: ${handleSize}px;
            background: #007bff;
            border: 1px solid #fff;
            cursor: ${pos.cursor};
            z-index: 1001;
        `;
        
        Object.keys(pos).forEach(key => {
            if (key !== 'cursor') {
                handle.style[key] = pos[key];
            }
        });
        
        img.parentElement.style.position = 'relative';
        img.parentElement.appendChild(handle);
        handles.push(handle);
    });
    
    return handles;
}

// Функция для обновления позиций индикаторов
function updateResizeHandles(handles, img) {
    // Обновляем позиции индикаторов в зависимости от размера изображения
    handles.forEach(handle => {
        // Логика обновления позиций
    });
}

// Функция для удаления индикаторов
function removeResizeHandles(handles) {
    handles.forEach(handle => {
        if (handle.parentElement) {
            handle.parentElement.removeChild(handle);
        }
    });
}

// Функция для создания галереи изображений
function createImageGallery() {
    const gallery = document.createElement('div');
    gallery.className = 'image-gallery';
    gallery.style.cssText = `
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 10px 0;
        background: #f9f9f9;
    `;
    
    // Добавляем кнопку для добавления изображений
    const addButton = document.createElement('button');
    addButton.textContent = 'Добавить изображения';
    addButton.style.cssText = `
        grid-column: 1 / -1;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    `;
    
    addButton.addEventListener('click', function() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.multiple = true;
        
        input.onchange = function() {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = `
                        width: 100%;
                        height: 150px;
                        object-fit: cover;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: transform 0.2s;
                    `;
                    
                    img.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.05)';
                    });
                    
                    img.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1)';
                    });
                    
                    img.addEventListener('click', function() {
                        insertImageToTextarea(this, document.querySelector('.editor'));
                    });
                    
                    gallery.insertBefore(img, addButton);
                };
                reader.readAsDataURL(file);
            });
        };
        
        input.click();
    });
    
    gallery.appendChild(addButton);
    return gallery;
}

// Функция для оптимизации изображений
function optimizeImage(img, maxWidth = 800, quality = 0.8) {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        const ratio = Math.min(maxWidth / img.width, maxWidth / img.height);
        canvas.width = img.width * ratio;
        canvas.height = img.height * ratio;
        
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        
        canvas.toBlob(resolve, 'image/jpeg', quality);
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    setupImageDragAndDrop();
    
    // Добавляем стили для индикаторов изменения размера
    const style = document.createElement('style');
    style.textContent = `
        .resize-handle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #007bff;
            border: 1px solid #fff;
            z-index: 1001;
        }
        
        .resize-handle:hover {
            background: #0056b3;
        }
        
        .image-drop-zone {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 123, 255, 0.1);
            border: 2px dashed #007bff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #007bff;
            z-index: 1000;
            pointer-events: none;
        }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            background: #f9f9f9;
        }
        
        .image-gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .image-gallery img:hover {
            transform: scale(1.05);
        }
    `;
    document.head.appendChild(style);
});
