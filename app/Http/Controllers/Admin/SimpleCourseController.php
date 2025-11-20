<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;
use App\Models\CourseTranslation;
use App\Models\User;
use App\Traits\FileUpload;
use Illuminate\Http\Request;

class SimpleCourseController extends Controller
{
    use FileUpload;

    /**
     * Display a listing of courses
     */
    public function index(Request $request, string $locale)
    {
        $query = Course::with(['instructor', 'category', 'translations']);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('translations', function($tq) use ($request) {
                      $tq->where('title', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->latest()->paginate(20);
        $categories = CourseCategory::orderBy('name')->get();

        return view('admin.simple-course.index', compact('courses', 'categories'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create(string $locale)
    {
        $instructors = User::where('role', 'instructor')
            ->where('approve_status', 'approved')
            ->orderBy('name')
            ->get();
        $categories = CourseCategory::orderBy('name')->get();
        $levels = CourseLevel::orderBy('name')->get();
        $languages = CourseLanguage::orderBy('name')->get();

        return view('admin.simple-course.create', compact('instructors', 'categories', 'levels', 'languages'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request, string $locale)
    {
        try {
            $request->validate([
                'instructor_id' => ['required', 'exists:users,id'],
                'category_id' => ['required', 'exists:course_categories,id'],
                'level_id' => ['required', 'exists:course_levels,id'],
                'language_id' => ['required', 'exists:course_languages,id'],
                'thumbnail' => ['required', 'image', 'max:3000'],
                'title' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0'],
                'discount' => ['nullable', 'numeric', 'min:0'],
                'duration' => ['required', 'numeric', 'min:1'],
                'capacity' => ['nullable', 'numeric', 'min:1'],
                'description' => ['required', 'string'],
                'status' => ['required', 'in:active,inactive,pending'],
                'translations.ru.title' => ['nullable', 'string', 'max:255'],
                'translations.ru.description' => ['nullable', 'string'],
            ]);

            // Upload thumbnail
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));

            // Create course
            $course = new Course();
            $course->instructor_id = $request->instructor_id;
            $course->category_id = $request->category_id;
            $course->course_level_id = $request->level_id;
            $course->course_language_id = $request->language_id;
            $course->title = $request->title;
            $course->slug = \Str::slug($request->title);
            $course->seo_description = $request->seo_description;
            $course->thumbnail = $thumbnailPath;
            $course->price = $request->price;
            $course->discount = $request->discount ?? 0;
            $course->duration = $request->duration;
            $course->capacity = $request->capacity;
            $course->description = $request->description;
            $course->status = $request->status;
            $course->is_approved = $request->is_approved ?? 0;
            $course->certificate = $request->certificate ?? 0;
            $course->qna = $request->qna ?? 0;
            $course->save();

            // Save translations
            foreach (['ru', 'kk', 'en'] as $lang) {
                $title = $request->input("translations.{$lang}.title");
                $description = $request->input("translations.{$lang}.description");
                $seoDescription = $request->input("translations.{$lang}.seo_description");

                if ($title || $description) {
                    CourseTranslation::create([
                        'course_id' => $course->id,
                        'locale' => $lang,
                        'title' => $title ?: $course->title,
                        'slug' => \Str::slug($title ?: $course->title),
                        'description' => $description ?: $course->description,
                        'seo_description' => $seoDescription ?: $course->seo_description,
                    ]);
                }
            }

            notyf()->success('Курс успешно создан!');
            return redirect()->route('admin.simple-courses.index', ['locale' => $locale]);

        } catch (\Exception $e) {
            \Log::error('Course creation failed: ' . $e->getMessage());
            notyf()->error('Ошибка при создании курса: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing a course
     */
    public function edit(string $locale, string $id)
    {
        $course = Course::with('translations')->findOrFail($id);
        $instructors = User::where('role', 'instructor')
            ->where('approve_status', 'approved')
            ->orderBy('name')
            ->get();
        $categories = CourseCategory::orderBy('name')->get();
        $levels = CourseLevel::orderBy('name')->get();
        $languages = CourseLanguage::orderBy('name')->get();

        return view('admin.simple-course.edit', compact('course', 'instructors', 'categories', 'levels', 'languages'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, string $locale, string $id)
    {
        try {
            $course = Course::findOrFail($id);

            $request->validate([
                'instructor_id' => ['required', 'exists:users,id'],
                'category_id' => ['required', 'exists:course_categories,id'],
                'level_id' => ['required', 'exists:course_levels,id'],
                'language_id' => ['required', 'exists:course_languages,id'],
                'thumbnail' => ['nullable', 'image', 'max:3000'],
                'title' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0'],
                'discount' => ['nullable', 'numeric', 'min:0'],
                'duration' => ['required', 'numeric', 'min:1'],
                'capacity' => ['nullable', 'numeric', 'min:1'],
                'description' => ['required', 'string'],
                'status' => ['required', 'in:active,inactive,pending'],
            ]);

            // Upload thumbnail if provided
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
                if ($course->thumbnail) {
                    $this->deleteFile($course->thumbnail);
                }
                $course->thumbnail = $thumbnailPath;
            }

            // Update course
            $course->instructor_id = $request->instructor_id;
            $course->category_id = $request->category_id;
            $course->course_level_id = $request->level_id;
            $course->course_language_id = $request->language_id;
            $course->title = $request->title;
            $course->slug = \Str::slug($request->title);
            $course->seo_description = $request->seo_description;
            $course->price = $request->price;
            $course->discount = $request->discount ?? 0;
            $course->duration = $request->duration;
            $course->capacity = $request->capacity;
            $course->description = $request->description;
            $course->status = $request->status;
            $course->is_approved = $request->is_approved ?? $course->is_approved;
            $course->certificate = $request->certificate ?? 0;
            $course->qna = $request->qna ?? 0;
            $course->save();

            // Update translations
            foreach (['ru', 'kk', 'en'] as $lang) {
                $title = $request->input("translations.{$lang}.title");
                $description = $request->input("translations.{$lang}.description");
                $seoDescription = $request->input("translations.{$lang}.seo_description");

                if ($title || $description) {
                    CourseTranslation::updateOrCreate(
                        ['course_id' => $course->id, 'locale' => $lang],
                        [
                            'title' => $title ?: $course->title,
                            'slug' => \Str::slug($title ?: $course->title),
                            'description' => $description ?: $course->description,
                            'seo_description' => $seoDescription ?: $course->seo_description,
                        ]
                    );
                } else {
                    CourseTranslation::where('course_id', $course->id)
                        ->where('locale', $lang)
                        ->delete();
                }
            }

            notyf()->success('Курс успешно обновлен!');
            return redirect()->route('admin.simple-courses.index', ['locale' => $locale]);

        } catch (\Exception $e) {
            \Log::error('Course update failed: ' . $e->getMessage());
            notyf()->error('Ошибка при обновлении курса: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified course
     */
    public function destroy(string $locale, string $id)
    {
        try {
            $course = Course::findOrFail($id);

            if ($course->thumbnail) {
                $this->deleteFile($course->thumbnail);
            }

            $course->delete();

            notyf()->success('Курс успешно удален!');
            return redirect()->route('admin.simple-courses.index', ['locale' => $locale]);

        } catch (\Exception $e) {
            \Log::error('Course deletion failed: ' . $e->getMessage());
            notyf()->error('Ошибка при удалении курса');
            return back();
        }
    }

    /**
     * Change course approval status
     */
    public function toggleApproval(string $locale, string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->is_approved = !$course->is_approved;
            $course->save();

            $status = $course->is_approved ? 'одобрен' : 'отклонен';
            notyf()->success("Курс {$status}!");

            return back();

        } catch (\Exception $e) {
            \Log::error('Course approval toggle failed: ' . $e->getMessage());
            notyf()->error('Ошибка при изменении статуса');
            return back();
        }
    }
}
