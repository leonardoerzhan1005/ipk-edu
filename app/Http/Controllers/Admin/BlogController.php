<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Traits\FileUpload;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class BlogController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $locale) : View
    {
        $query = Blog::with(['category', 'translations']);
        
        // Поиск по заголовку
        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }
        
        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }
        
        $blogs = $query->paginate(20);
        $categories = BlogCategory::with('translations')->get();
        
        return view('admin.blog.index', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $locale) : View
    {
        $categories = BlogCategory::with('translations')->get();
         $blog = new Blog(); // создаём пустой объект
        return view('admin.blog.create', compact('categories', 'blog'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request, string $locale) : RedirectResponse
{
    try {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'category' => ['nullable', 'exists:blog_categories,id'],
            'status' => ['nullable', 'boolean'],
            'translations.ru.title' => ['required', 'string', 'max:255'],
            'translations.ru.description' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Загружаем изображение
        $image = $request->hasFile('image')
            ? $this->uploadFile($request->file('image'))
            : '/default-files/default-blog-image.png';

        $adminUser = adminUser();
        if (!$adminUser) {
            notyf()->error('Admin authentication required');
            return back()->withInput();
        }

        // ✅ Создаём блог
        $blog = new Blog();
        $blog->image = $image;
        $blog->blog_category_id = $request->category ?: null;
        $blog->user_id = $adminUser->id;
        $blog->status = $request->status ?? 0;
        $blog->published_at = $request->published_at ?: now(); // <-- сюда переместили
        $blog->save();

        // ✅ Сохраняем переводы
        foreach (['ru', 'kk', 'en'] as $lang) {
            $title = $request->input("translations.{$lang}.title");
            $description = $request->input("translations.{$lang}.description");
            $seoTitle = $request->input("translations.{$lang}.seo_title");
            $seoDescription = $request->input("translations.{$lang}.seo_description");

            if ($lang === 'ru') {
                if ($title && $description) {
                    $blog->translations()->create([
                        'locale' => $lang,
                        'title' => $title,
                        'slug' => \Str::slug($title),
                        'description' => $description,
                        'seo_title' => $seoTitle,
                        'seo_description' => $seoDescription,
                    ]);
                }
            } else {
                if ($title || $description) {
                    $blog->translations()->create([
                        'locale' => $lang,
                        'title' => $title,
                        'slug' => \Str::slug($title ?: 'blog-' . $blog->id . '-' . $lang),
                        'description' => $description,
                        'seo_title' => $seoTitle,
                        'seo_description' => $seoDescription,
                    ]);
                }
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.blogs.index', ['locale' => $locale]);
    } catch (\Exception $e) {
        \Log::error('Blog creation failed: ' . $e->getMessage());
        notyf()->error('Failed to create blog: ' . $e->getMessage());
        return back()->withInput();
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, Blog $blog) : View
    {
        $blog->load('translations');
        $categories = BlogCategory::with('translations')->get();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $locale, Blog $blog) : RedirectResponse
{
    try {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'category' => ['nullable', 'exists:blog_categories,id'],
            'status' => ['nullable', 'boolean'],
            'translations.ru.title' => ['required', 'string', 'max:255'],
            'translations.ru.description' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('image')) {
            $image = $this->uploadFile($request->file('image'));
            if ($request->old_image && !str_contains($request->old_image, 'default-files/')) {
                $this->deleteFile($request->old_image);
            }
            $blog->image = $image;
        }

        $blog->blog_category_id = $request->category ?: null;
        $blog->status = $request->status ?? 0;
        $blog->published_at = $request->published_at ?: $blog->published_at; // <-- обновляем дату
        $blog->save(); // <-- сохраняем перед циклами

        // ✅ Обновляем переводы
        foreach (['ru', 'kk', 'en'] as $lang) {
            $title = $request->input("translations.{$lang}.title");
            $description = $request->input("translations.{$lang}.description");
            $seoTitle = $request->input("translations.{$lang}.seo_title");
            $seoDescription = $request->input("translations.{$lang}.seo_description");

            if ($lang === 'ru') {
                if ($title && $description) {
                    $blog->translations()->updateOrCreate(
                        ['locale' => $lang],
                        [
                            'title' => $title,
                            'slug' => \Str::slug($title),
                            'description' => $description,
                            'seo_title' => $seoTitle,
                            'seo_description' => $seoDescription,
                        ]
                    );
                }
            } else {
                if ($title || $description) {
                    $blog->translations()->updateOrCreate(
                        ['locale' => $lang],
                        [
                            'title' => $title,
                            'slug' => \Str::slug($title ?: 'blog-' . $blog->id . '-' . $lang),
                            'description' => $description,
                            'seo_title' => $seoTitle,
                            'seo_description' => $seoDescription,
                        ]
                    );
                } else {
                    $blog->translations()->where('locale', $lang)->delete();
                }
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.blogs.index', ['locale' => $locale]);
    } catch (\Exception $e) {
        \Log::error('Blog update failed: ' . $e->getMessage());
        notyf()->error('Failed to update blog: ' . $e->getMessage());
        return back()->withInput();
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, Blog $blog) : Response
    {
        try {
            $this->deleteFile($blog->image);
            $blog->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        }catch(Exception $e) {
            logger("Social Link Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
    
    /**
     * Upload image from TinyMCE editor (for drag & drop and paste)
     */
    public function uploadEditorImage(Request $request)
    {
        try {
            $request->validate([
                'file' => ['required', 'image', 'max:5000'], // 5MB max
            ]);
            
            if ($request->hasFile('file')) {
                $imagePath = $this->uploadFile($request->file('file'));
                
                return response()->json([
                    'location' => asset($imagePath)
                ], 200);
            }
            
            return response()->json([
                'error' => 'No file uploaded'
            ], 400);
            
        } catch (\Exception $e) {
            \Log::error('Editor image upload failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
