<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    /**
     * Конвертирует размер из формата ini (например, "50M", "2G") в байты
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int)$value;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }

    public function index(string $locale): View
    {
        $query = Document::where('locale', $locale)->orderBy('published_at', 'desc');
        if ($category = request('category')) {
            $query->where('category', $category);
        }
        $documents = $query->paginate(20)->withQueryString();
        return view('admin.documents.index', compact('documents'));
    }

    public function create(string $locale): View
    {
        return view('admin.documents.create');
    }

    public function store(Request $request, string $locale): RedirectResponse
    {
        // Проверяем наличие файла
        if (!$request->hasFile('file')) {
            // Получаем информацию о PHP настройках для диагностики
            $uploadMaxFilesize = ini_get('upload_max_filesize');
            $postMaxSize = ini_get('post_max_size');
            $contentLength = $request->server('CONTENT_LENGTH');
            
            // Конвертируем размеры в байты для сравнения
            $uploadMaxBytes = $this->convertToBytes($uploadMaxFilesize);
            $postMaxBytes = $this->convertToBytes($postMaxSize);
            
            // Проверяем размер контента
            if ($contentLength) {
                $contentLengthInt = (int)$contentLength;
                if ($contentLengthInt > $postMaxBytes) {
                    return back()->withErrors([
                        'file' => "Файл слишком большой. Размер запроса: " . round($contentLengthInt / 1024 / 1024, 2) . " МБ. " .
                                  "Текущий лимит post_max_size: {$postMaxSize}. " .
                                  "Увеличьте post_max_size в настройках PHP."
                    ])->withInput();
                }
                if ($contentLengthInt > $uploadMaxBytes) {
                    return back()->withErrors([
                        'file' => "Файл слишком большой. Размер запроса: " . round($contentLengthInt / 1024 / 1024, 2) . " МБ. " .
                                  "Текущий лимит upload_max_filesize: {$uploadMaxFilesize}. " .
                                  "Увеличьте upload_max_filesize в настройках PHP."
                    ])->withInput();
                }
            }
            
            // Если размер контента не определен, но файл отсутствует - возможно файл был отклонен
            return back()->withErrors([
                'file' => "Файл не был загружен. " .
                          "Текущие настройки PHP: upload_max_filesize = {$uploadMaxFilesize}, post_max_size = {$postMaxSize}. " .
                          "Проверьте, что файл не превышает эти лимиты."
            ])->withInput();
        }

        $file = $request->file('file');
        
        // Проверяем валидность файла
        if (!$file->isValid()) {
            $errorCode = $file->getError();
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'Файл слишком большой. Превышен лимит upload_max_filesize.',
                UPLOAD_ERR_FORM_SIZE => 'Файл слишком большой. Превышен лимит post_max_size.',
                UPLOAD_ERR_PARTIAL => 'Файл был загружен частично.',
                UPLOAD_ERR_NO_FILE => 'Файл не был загружен.',
                UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка для загрузки файлов.',
                UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
                UPLOAD_ERR_EXTENSION => 'Загрузка файла была остановлена расширением PHP.',
            ];
            $errorMessage = $errorMessages[$errorCode] ?? 'Ошибка при загрузке файла. Код ошибки: ' . $errorCode;
            return back()->withErrors(['file' => $errorMessage])->withInput();
        }

        // Теперь валидируем остальные поля и файл
        try {
            $data = $request->validate([
                'category' => ['required', 'in:normative,orders,manuals,templates'],
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip,rar', 'max:51200'],
                'published_at' => ['nullable', 'date'],
            ], [
                'file.required' => 'Файл обязателен для загрузки.',
                'file.file' => 'Загруженный файл недействителен.',
                'file.mimes' => 'Файл должен быть одного из типов: PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR.',
                'file.max' => 'Размер файла не должен превышать 50 МБ. Текущий размер файла слишком большой.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Если ошибка связана с файлом, добавляем более понятное сообщение
            if ($e->errors() && isset($e->errors()['file'])) {
                $fileErrors = $e->errors()['file'];
                if (in_array('The file field is required.', $fileErrors) || in_array('Загрузка поля file не удалась.', $fileErrors)) {
                    return back()->withErrors(['file' => 'Файл не был загружен. Возможно, файл слишком большой или произошла ошибка при загрузке. Проверьте настройки PHP (upload_max_filesize, post_max_size).'])->withInput();
                }
            }
            throw $e;
        }

        try {
            $storedPath = Storage::disk('public')->putFile('uploads/documents', $file);
            // Save path as '/uploads/...', not '/storage/uploads/...'
            $data['file_path'] = $storedPath;
            $data['file_format'] = strtoupper($file->getClientOriginalExtension());
            $data['file_size'] = round($file->getSize() / 1024 / 1024, 2) . ' MB';
        } catch (\Throwable $e) {
            Log::error('Document upload failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['file' => 'Failed to save file. Check storage permissions or size limits.'])->withInput();
        }
        $data['locale'] = $locale;
        $data['status'] = $request->has('status');

        Document::create($data);

        notyf()->success('Created Successfully!');
        return to_route('admin.documents.index', ['locale' => $locale]);
    }

    public function edit(string $locale, Document $document): View
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, string $locale, Document $document): RedirectResponse
    {
        // Валидируем основные поля сначала
        $data = $request->validate([
            'category' => ['required', 'in:normative,orders,manuals,templates'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Проверяем валидность файла до валидации
            if (!$file->isValid()) {
                $errorCode = $file->getError();
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'Файл слишком большой. Максимальный размер: 50 МБ. Проверьте настройки PHP (upload_max_filesize).',
                    UPLOAD_ERR_FORM_SIZE => 'Файл слишком большой. Максимальный размер формы превышен (post_max_size).',
                    UPLOAD_ERR_PARTIAL => 'Файл был загружен частично.',
                    UPLOAD_ERR_NO_FILE => 'Файл не был загружен.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка для загрузки файлов.',
                    UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
                    UPLOAD_ERR_EXTENSION => 'Загрузка файла была остановлена расширением PHP.',
                ];
                $errorMessage = $errorMessages[$errorCode] ?? 'Ошибка при загрузке файла. Код ошибки: ' . $errorCode;
                return back()->withErrors(['file' => $errorMessage])->withInput();
            }

            // Валидируем файл
            try {
                $request->validate([
                    'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip,rar', 'max:51200'],
                ], [
                    'file.file' => 'Загруженный файл недействителен.',
                    'file.mimes' => 'Файл должен быть одного из типов: PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR.',
                    'file.max' => 'Размер файла не должен превышать 50 МБ. Текущий размер файла слишком большой.',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($e->errors() && isset($e->errors()['file'])) {
                    $fileErrors = $e->errors()['file'];
                    if (in_array('Загрузка поля file не удалась.', $fileErrors)) {
                        return back()->withErrors(['file' => 'Файл не был загружен. Возможно, файл слишком большой или произошла ошибка при загрузке. Проверьте настройки PHP (upload_max_filesize, post_max_size).'])->withInput();
                    }
                }
                throw $e;
            }
            try {
                $storedPath = Storage::disk('public')->putFile('uploads/documents', $file);
                // Save path as '/uploads/...', not '/storage/uploads/...'
                $data['file_path'] = $storedPath;
                $data['file_format'] = strtoupper($file->getClientOriginalExtension());
                $data['file_size'] = round($file->getSize() / 1024 / 1024, 2) . ' MB';
            } catch (\Throwable $e) {
                Log::error('Document upload failed (update)', ['error' => $e->getMessage()]);
                return back()->withErrors(['file' => 'Failed to save file. Check storage permissions or size limits.'])->withInput();
            }
        }

        $data['status'] = $request->has('status');
        $document->update($data);

        notyf()->success('Update Successfully!');
        return to_route('admin.documents.index', ['locale' => $locale]);
    }

    public function destroy(string $locale, Document $document): RedirectResponse
    {
        $document->delete();
        notyf()->success('Deleted Successfully!');
        return to_route('admin.documents.index', ['locale' => $locale]);
    }
}


