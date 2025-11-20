<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialTranslation;

use App\Traits\FileUpload;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use FileUpload;

    /**
     * Display a listing of the resource.
     */
    public function index(string $locale) : View
    {
        $testimonials = Testimonial::with('translations')->paginate(20);
        return view('admin.sections.testimonial.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $locale) : View
    {
        return view('admin.sections.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $locale, Request $request)
    {
        $request->validate([
            'rating' => ['required', 'numeric'],
            'image'  => ['required', 'image', 'max:3000'],
            'translations.ru.review' => ['required', 'string', 'max:1000'],
            'translations.ru.name'   => ['required', 'string', 'max:255'],
            'translations.ru.title'  => ['required', 'string', 'max:255'],
            'translations.kk.review' => ['nullable', 'string', 'max:1000'],
            'translations.kk.name'   => ['nullable', 'string', 'max:255'],
            'translations.kk.title'  => ['nullable', 'string', 'max:255'],
            'translations.en.review' => ['nullable', 'string', 'max:1000'],
            'translations.en.name'   => ['nullable', 'string', 'max:255'],
            'translations.en.title'  => ['nullable', 'string', 'max:255'],
        ]);

        $image = $this->uploadFile($request->file('image'));

        $testimonial = new Testimonial();
        $testimonial->rating     = $request->rating;
        $testimonial->user_image = $image;
        $testimonial->save();

        // Сохраняем переводы для всех языков
        foreach (['ru', 'kk', 'en'] as $lang) {
            $review = $request->input("translations.{$lang}.review");
            $name = $request->input("translations.{$lang}.name");
            $title = $request->input("translations.{$lang}.title");
            
            if ($review || $name || $title) {
                TestimonialTranslation::create([
                    'testimonial_id' => $testimonial->id,
                    'locale' => $lang,
                    'review' => $review,
                    'user_name' => $name,
                    'user_title' => $title,
                ]);
            }
        }

        notyf()->success("Created Successfully!");

        return redirect()->route('admin.testimonial-section.index', $locale);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, Testimonial $testimonial) : View
    {
        $testimonial->load('translations');
        return view('admin.sections.testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $locale, Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'rating' => ['required', 'numeric'],
            'image'  => ['nullable', 'image', 'max:3000'],
            'translations.ru.review' => ['required', 'string', 'max:1000'],
            'translations.ru.name'   => ['required', 'string', 'max:255'],
            'translations.ru.title'  => ['required', 'string', 'max:255'],
            'translations.kk.review' => ['nullable', 'string', 'max:1000'],
            'translations.kk.name'   => ['nullable', 'string', 'max:255'],
            'translations.kk.title'  => ['nullable', 'string', 'max:255'],
            'translations.en.review' => ['nullable', 'string', 'max:1000'],
            'translations.en.name'   => ['nullable', 'string', 'max:255'],
            'translations.en.title'  => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $image = $this->uploadFile($request->file('image'));
            $this->deleteFile($testimonial->user_image);
            $testimonial->user_image = $image;
        }

        $testimonial->rating = $request->rating;
        $testimonial->save();

        // Обновляем переводы для всех языков
        foreach (['ru', 'kk', 'en'] as $lang) {
            $review = $request->input("translations.{$lang}.review");
            $name = $request->input("translations.{$lang}.name");
            $title = $request->input("translations.{$lang}.title");
            
            if ($review || $name || $title) {
                TestimonialTranslation::updateOrCreate(
                    ['testimonial_id' => $testimonial->id, 'locale' => $lang],
                    [
                        'review' => $review,
                        'user_name' => $name,
                        'user_title' => $title,
                    ]
                );
            } else {
                // Удаляем перевод, если все поля пустые
                TestimonialTranslation::where('testimonial_id', $testimonial->id)
                    ->where('locale', $lang)
                    ->delete();
            }
        }

        notyf()->success("Updated Successfully!");

        return redirect()->route('admin.testimonial-section.index', $locale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, Testimonial $testimonial)
    {
        try {
            $this->deleteFile($testimonial->user_image);
            $testimonial->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Testimonial Delete Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}
