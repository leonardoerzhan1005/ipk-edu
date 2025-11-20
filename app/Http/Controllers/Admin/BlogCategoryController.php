<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogCategoryTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Colors\Rgb\Channels\Red;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $locale) : View
    {
        $categories = BlogCategory::with('translations')
            ->withCount('blogs')
            ->paginate(20);
        return view('admin.blog.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $locale) : View
    {
        return view('admin.blog.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $locale, Request $request) : RedirectResponse
    {
        $request->validate([
            'status' => ['nullable', 'boolean'],
            'translations.ru.name' => ['required', 'string', 'max:255'],
            'translations.kk.name' => ['nullable', 'string', 'max:255'],
            'translations.en.name' => ['nullable', 'string', 'max:255'],
        ]);

        $category = new BlogCategory();
        $category->status = $request->status ?? 0;
        $category->save();

        // Сохраняем переводы для всех языков
        foreach (['ru', 'kk', 'en'] as $lang) {
            $name = $request->input("translations.{$lang}.name");
            
            if ($name) {
                BlogCategoryTranslation::create([
                    'blog_category_id' => $category->id,
                    'locale' => $lang,
                    'name' => $name,
                    'slug' => \Str::slug($name),
                ]);
            }
        }

        notyf()->success('Created Successfully!');

        return to_route('admin.blog-categories.index', ['locale' => $locale]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, BlogCategory $blog_category) : View
    {
        $blog_category->load('translations');
        $category = $blog_category;
        return view('admin.blog.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $locale, BlogCategory $blog_category) : RedirectResponse
    {
        $request->validate([
            'status' => ['nullable', 'boolean'],
            'translations.ru.name' => ['required', 'string', 'max:255'],
            'translations.kk.name' => ['nullable', 'string', 'max:255'],
            'translations.en.name' => ['nullable', 'string', 'max:255'],
        ]);

        $category = $blog_category;
        $category->status = $request->status ?? 0;
        $category->save();

        // Обновляем переводы для всех языков
        foreach (['ru', 'kk', 'en'] as $lang) {
            $name = $request->input("translations.{$lang}.name");
            
            if ($name) {
                BlogCategoryTranslation::updateOrCreate(
                    ['blog_category_id' => $category->id, 'locale' => $lang],
                    [
                        'name' => $name,
                        'slug' => \Str::slug($name),
                    ]
                );
            } else {
                // Удаляем перевод, если поле пустое
                BlogCategoryTranslation::where('blog_category_id', $category->id)
                    ->where('locale', $lang)
                    ->delete();
            }
        }

        notyf()->success('Update Successfully!');

        return to_route('admin.blog-categories.index', ['locale' => $locale]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, BlogCategory $blog_category)
    {
        try {
            $blog_category->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        }catch(Exception $e) {
            logger("Social Link Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}
