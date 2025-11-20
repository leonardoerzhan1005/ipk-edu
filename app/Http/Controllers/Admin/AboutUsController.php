<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\AboutUsTranslation;
use App\Traits\FileUpload;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AboutUsController extends Controller
{
    use FileUpload;

    /**
     * Display a listing of the resource.
     */
    public function index(string $locale): View
    {
        $items = AboutUs::with('translations')->orderBy('order', 'asc')->paginate(20);
        return view('admin.about-us.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $locale): View
    {
        return view('admin.about-us.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $locale): RedirectResponse
    {
        try {
            $request->validate([
                'image' => ['nullable', 'image', 'max:3000'],
                'order' => ['nullable', 'integer'],
                'status' => ['nullable', 'boolean'],
                'translations.ru.title' => ['required', 'string', 'max:255'],
                'translations.ru.subtitle' => ['nullable', 'string', 'max:255'],
                'translations.ru.description' => ['required', 'string'],
            ]);

            $image = $request->hasFile('image')
                ? $this->uploadFile($request->file('image'))
                : null;

            // Создаём запись
            $aboutUs = new AboutUs();
            $aboutUs->image = $image;
            $aboutUs->order = $request->order ?? 0;
            $aboutUs->status = $request->status ?? 1;
            $aboutUs->save();

            // Сохраняем переводы
            foreach (['ru', 'kk', 'en'] as $lang) {
                $title = $request->input("translations.{$lang}.title");
                $subtitle = $request->input("translations.{$lang}.subtitle");
                $description = $request->input("translations.{$lang}.description");

                if ($lang === 'ru') {
                    // Русский обязателен
                    if ($title && $description) {
                        $aboutUs->translations()->create([
                            'locale' => $lang,
                            'title' => $title,
                            'subtitle' => $subtitle,
                            'description' => $description,
                        ]);
                    }
                } else {
                    // Другие языки опциональны
                    if ($title || $description) {
                        $aboutUs->translations()->create([
                            'locale' => $lang,
                            'title' => $title,
                            'subtitle' => $subtitle,
                            'description' => $description,
                        ]);
                    }
                }
            }

            notyf()->success('Создано успешно!');
            return to_route('admin.about-us.index', ['locale' => $locale]);
        } catch (\Exception $e) {
            \Log::error('About Us creation failed: ' . $e->getMessage());
            notyf()->error('Ошибка при создании: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, AboutUs $about_us): View
    {
        $about_us->load('translations');
        return view('admin.about-us.edit', compact('about_us'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $locale, AboutUs $about_us): RedirectResponse
    {
        try {
            $request->validate([
                'image' => ['nullable', 'image', 'max:3000'],
                'order' => ['nullable', 'integer'],
                'status' => ['nullable', 'boolean'],
                'translations.ru.title' => ['required', 'string', 'max:255'],
                'translations.ru.subtitle' => ['nullable', 'string', 'max:255'],
                'translations.ru.description' => ['required', 'string'],
            ]);

            if ($request->hasFile('image')) {
                $image = $this->uploadFile($request->file('image'));
                if ($about_us->image) {
                    $this->deleteFile($about_us->image);
                }
                $about_us->image = $image;
            }

            $about_us->order = $request->order ?? 0;
            $about_us->status = $request->status ?? 1;
            $about_us->save();

            // Обновляем переводы
            foreach (['ru', 'kk', 'en'] as $lang) {
                $title = $request->input("translations.{$lang}.title");
                $subtitle = $request->input("translations.{$lang}.subtitle");
                $description = $request->input("translations.{$lang}.description");

                if ($lang === 'ru') {
                    // Русский обязателен
                    if ($title && $description) {
                        $about_us->translations()->updateOrCreate(
                            ['locale' => $lang],
                            [
                                'title' => $title,
                                'subtitle' => $subtitle,
                                'description' => $description,
                            ]
                        );
                    }
                } else {
                    // Другие языки опциональны
                    if ($title || $description) {
                        $about_us->translations()->updateOrCreate(
                            ['locale' => $lang],
                            [
                                'title' => $title,
                                'subtitle' => $subtitle,
                                'description' => $description,
                            ]
                        );
                    } else {
                        // Удаляем перевод, если все поля пустые
                        $about_us->translations()->where('locale', $lang)->delete();
                    }
                }
            }

            notyf()->success('Обновлено успешно!');
            return to_route('admin.about-us.index', ['locale' => $locale]);
        } catch (\Exception $e) {
            \Log::error('About Us update failed: ' . $e->getMessage());
            notyf()->error('Ошибка при обновлении: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, AboutUs $about_us): Response
    {
        try {
            if ($about_us->image) {
                $this->deleteFile($about_us->image);
            }
            $about_us->delete();
            notyf()->success('Удалено успешно!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("About Us Delete Error >> " . $e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}
