<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use FileUpload;
    public function index(Request $request, string $locale): View
    {
        $services = Service::orderBy('display_order')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create(string $locale): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request, string $locale): RedirectResponse
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'button_label' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],

            'translations.ru.title' => ['required', 'string', 'max:255'],
            'translations.ru.subtitle' => ['nullable', 'string', 'max:500'],
            'translations.ru.left_items' => ['nullable', 'array'],
            'translations.ru.left_items.*' => ['nullable', 'string', 'max:255'],
            'translations.ru.right_items' => ['nullable', 'array'],
            'translations.ru.right_items.*' => ['nullable', 'string', 'max:255'],

            'translations.kk.title' => ['nullable', 'string', 'max:255'],
            'translations.kk.subtitle' => ['nullable', 'string', 'max:500'],
            'translations.kk.left_items' => ['nullable', 'array'],
            'translations.kk.left_items.*' => ['nullable', 'string', 'max:255'],
            'translations.kk.right_items' => ['nullable', 'array'],
            'translations.kk.right_items.*' => ['nullable', 'string', 'max:255'],

            'translations.en.title' => ['nullable', 'string', 'max:255'],
            'translations.en.subtitle' => ['nullable', 'string', 'max:500'],
            'translations.en.left_items' => ['nullable', 'array'],
            'translations.en.left_items.*' => ['nullable', 'string', 'max:255'],
            'translations.en.right_items' => ['nullable', 'array'],
            'translations.en.right_items.*' => ['nullable', 'string', 'max:255'],
        ]);

        // Additional validation: require at least one non-empty RU list item
        $leftRu = collect($request->input('translations.ru.left_items', []))->filter(fn($v) => trim((string)$v) !== '');
        $rightRu = collect($request->input('translations.ru.right_items', []))->filter(fn($v) => trim((string)$v) !== '');
        if ($leftRu->isEmpty() && $rightRu->isEmpty()) {
            return back()->withErrors(['translations.ru.left_items' => 'Заполните хотя бы один пункт (RU).'])->withInput();
        }

        $image = $request->hasFile('image')
            ? $this->uploadFile($request->file('image'))
            : null;

        $service = new Service();
        $service->image = $image;
        $service->button_label = $request->button_label;
        $service->button_link = $request->button_link;
        $service->status = $request->boolean('status');
        $service->display_order = $request->input('display_order', 0);
        $service->save();

        foreach (['ru','kk','en'] as $lang) {
            $title = $request->input("translations.$lang.title");
            $subtitle = $request->input("translations.$lang.subtitle");
            $left = $request->input("translations.$lang.left_items", []);
            $right = $request->input("translations.$lang.right_items", []);
            if ($lang === 'ru') {
                if ($title) {
                    $service->translations()->create([
                        'locale' => $lang,
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'left_items' => array_values(array_filter($left)),
                        'right_items' => array_values(array_filter($right)),
                    ]);
                }
            } else {
                if ($title || $subtitle || !empty(array_filter($left)) || !empty(array_filter($right))) {
                    $service->translations()->create([
                        'locale' => $lang,
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'left_items' => array_values(array_filter($left)),
                        'right_items' => array_values(array_filter($right)),
                    ]);
                }
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.services.index', ['locale' => $locale]);
    }

    public function edit(string $locale, Service $service): View
    {
        $service->load('translations');
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, string $locale, Service $service): RedirectResponse
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'button_label' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],

            'translations.ru.title' => ['required', 'string', 'max:255'],
            'translations.ru.subtitle' => ['nullable', 'string', 'max:500'],
            'translations.ru.left_items' => ['nullable', 'array'],
            'translations.ru.left_items.*' => ['nullable', 'string', 'max:255'],
            'translations.ru.right_items' => ['nullable', 'array'],
            'translations.ru.right_items.*' => ['nullable', 'string', 'max:255'],

            'translations.kk.title' => ['nullable', 'string', 'max:255'],
            'translations.kk.subtitle' => ['nullable', 'string', 'max:500'],
            'translations.kk.left_items' => ['nullable', 'array'],
            'translations.kk.left_items.*' => ['nullable', 'string', 'max:255'],
            'translations.kk.right_items' => ['nullable', 'array'],
            'translations.kk.right_items.*' => ['nullable', 'string', 'max:255'],

            'translations.en.title' => ['nullable', 'string', 'max:255'],
            'translations.en.subtitle' => ['nullable', 'string', 'max:500'],
            'translations.en.left_items' => ['nullable', 'array'],
            'translations.en.left_items.*' => ['nullable', 'string', 'max:255'],
            'translations.en.right_items' => ['nullable', 'array'],
            'translations.en.right_items.*' => ['nullable', 'string', 'max:255'],
        ]);

        // Additional validation: require at least one non-empty RU list item
        $leftRu = collect($request->input('translations.ru.left_items', []))->filter(fn($v) => trim((string)$v) !== '');
        $rightRu = collect($request->input('translations.ru.right_items', []))->filter(fn($v) => trim((string)$v) !== '');
        if ($leftRu->isEmpty() && $rightRu->isEmpty()) {
            return back()->withErrors(['translations.ru.left_items' => 'Заполните хотя бы один пункт (RU).'])->withInput();
        }

        if ($request->hasFile('image')) {
            $image = $this->uploadFile($request->file('image'));
            if ($service->image && !str_contains($service->image, 'default-files/')) {
                $this->deleteFile($service->image);
            }
            $service->image = $image;
        }

        $service->button_label = $request->button_label;
        $service->button_link = $request->button_link;
        $service->status = $request->boolean('status');
        $service->display_order = $request->input('display_order', $service->display_order);
        $service->save();

        foreach (['ru','kk','en'] as $lang) {
            $title = $request->input("translations.$lang.title");
            $subtitle = $request->input("translations.$lang.subtitle");
            $left = $request->input("translations.$lang.left_items", []);
            $right = $request->input("translations.$lang.right_items", []);

            if ($lang === 'ru') {
                if ($title) {
                    $service->translations()->updateOrCreate(
                        ['locale' => $lang],
                        [
                            'title' => $title,
                            'subtitle' => $subtitle,
                            'left_items' => array_values(array_filter($left)),
                            'right_items' => array_values(array_filter($right)),
                        ]
                    );
                }
            } else {
                if ($title || $subtitle || !empty(array_filter($left)) || !empty(array_filter($right))) {
                    $service->translations()->updateOrCreate(
                        ['locale' => $lang],
                        [
                            'title' => $title,
                            'subtitle' => $subtitle,
                            'left_items' => array_values(array_filter($left)),
                            'right_items' => array_values(array_filter($right)),
                        ]
                    );
                } else {
                    $service->translations()->where('locale', $lang)->delete();
                }
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.services.index', ['locale' => $locale]);
    }

    public function destroy(string $locale, Service $service): RedirectResponse
    {
        if ($service->image) {
            $this->deleteFile($service->image);
        }
        $service->delete();
        notyf()->success('Deleted Successfully!');
        return back();
    }
}


