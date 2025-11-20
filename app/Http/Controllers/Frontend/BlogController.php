<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //
    function index(Request $request) : View
    {
        $locale = app()->getLocale();
        $blogs = Blog::with(['author', 'translations', 'category.translations'])
        ->where('status', 1)
        ->when($request->filled('search'), function ($query) use ($request, $locale) {
            $query->whereHas('translations', function($q) use ($request, $locale) {
                $q->where('locale', $locale)
                  ->where(function($qq) use ($request) {
                      $qq->where('title', 'like', '%' . $request->search . '%')
                         ->orWhere('description', 'like', '%' . $request->search . '%');
                  });
            });
        })
        ->when($request->filled('category'), function ($query) use ($request, $locale) {
            $slug = $request->category;
            $query->whereHas('category.translations', function ($q) use ($slug, $locale) {
                $q->where('locale', $locale)->where('slug', $slug);
            });
        })
        ->paginate(20);
        
        // Получаем категории для фильтрации
        $blogCategories = BlogCategory::with('translations')
            ->withCount('blogs')
            ->where('status', 1)
            ->get();
        
        return view('frontend.pages.blog', compact('blogs', 'blogCategories'));     
    }

    function show(string $locale, string $slug) : View
    {
        $blog = Blog::with(['author', 'category.translations', 'comments', 'translations'])
            ->whereHas('translations', function($t) use ($slug, $locale) {
                $t->where('locale', $locale)->where('slug', $slug);
            })
            ->where('status', 1)
            ->firstOrFail();
            
        $recentBlogs = Blog::with(['translations'])
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->whereHas('translations', function($t) use ($locale) {
                $t->where('locale', $locale);
            })
            ->latest()
            ->take(3)
            ->get();
            
        $blogCategories = BlogCategory::with(['translations'])
            ->withCount('blogs')
            ->where('status', 1)
            ->get();
        
        return view('frontend.pages.blog-detail', compact('blog', 'recentBlogs', 'blogCategories'));     
    }

    function storeComment(Request $request, string $locale, string $id) : RedirectResponse 
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:500']
        ]);

        $blog = Blog::findOrFail($id);
        $blog->comments()->create([
            'comment' => $request->comment,
            'user_id' => user()->id,
            'blog_id' => $blog->id
        ]);

        notyf()->success('Comment Added Successfully!');
        return redirect()->back();
        
    }
}
