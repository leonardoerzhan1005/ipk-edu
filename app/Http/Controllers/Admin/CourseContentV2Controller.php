<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseChapterLession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class CourseContentV2Controller extends Controller
{
    public function index(): View
    {
        $courses = Course::with('instructor')->orderByDesc('id')->paginate(20);
        return view('admin.course.contents-v2.index', compact('courses'));
    }

    public function manage($locale, $courseId)
    {
        $courseId = (int) $courseId;
        $course = Course::find($courseId);
        if (!$course) {
            notyf()->error('Course not found');
            return redirect()->route('admin.course-contents-v2.index', ['locale' => $locale]);
        }
        $chapters = CourseChapter::where(['course_id' => $courseId])->with('lessons')->orderBy('order')->get();
        return view('admin.course.contents-v2.manage', compact('course', 'chapters'));
    }

    public function createChapterModal($locale, string $course): string
    {
        $id = (int) $course;
        return view('admin.course.contents-v2.partials.chapter-modal', [
            'id' => $id,
            'action' => route('admin.course-contents-v2.chapters.store', ['locale' => $locale, 'course' => $id])
        ])->render();
    }

    public function storeChapter(Request $request, $locale, string $courseId): RedirectResponse
    {
        $request->validate(['title' => ['required', 'max:255']]);
        $course = Course::findOrFail($courseId);
        $chapter = new CourseChapter();
        $chapter->title = $request->title;
        $chapter->course_id = (int)$courseId;
        $chapter->instructor_id = $course->instructor_id;
        $chapter->order = CourseChapter::where('course_id', $courseId)->count() + 1;
        $chapter->save();
        notyf()->success('Created Successfully!');
        return redirect()->back();
    }

    public function editChapterModal($locale, string $id): string
    {
        $editMode = true;
        $chapter = CourseChapter::where(['id' => $id])->firstOrFail();
        return view('admin.course.contents-v2.partials.chapter-modal', [
            'chapter' => $chapter,
            'editMode' => $editMode,
            'action' => route('admin.course-contents-v2.chapters.update', ['locale' => $locale, 'chapter' => $chapter->id])
        ])->render();
    }

    public function updateChapterModal(Request $request, $locale, string $id): RedirectResponse
    {
        $request->validate(['title' => ['required', 'max:255']]);
        $chapter = CourseChapter::findOrFail($id);
        $chapter->title = $request->title;
        $chapter->save();
        notyf()->success('Updated Successfully!');
        return redirect()->back();
    }

    public function destroyChapter($locale, string $id): Response
    {
        try {
            $chapter = CourseChapter::findOrFail($id);
            $chapter->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            return response(['message' => 'Something went wrong!'], 500);
        }
    }

    public function createLesson(Request $request): string
    {
        $courseId = $request->course_id;
        $chapterId = $request->chapter_id;
        return view('admin.course.contents-v2.partials.lesson-modal', compact('courseId', 'chapterId'))->render();
    }

    public function storeLesson(Request $request): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string'],
            'file_type' => ['required', 'in:video,audio,file,pdf,doc'],
            'duration' => ['required'],
            'is_preview' => ['nullable', 'boolean'],
            'downloadable' => ['nullable', 'boolean'],
            'description' => ['required']
        ];
        if ($request->filled('file')) { $rules['file'] = ['required']; } else { $rules['url'] = ['required']; }
        $request->validate($rules);

        $lesson = new CourseChapterLession();
        $lesson->title = $request->title;
        $lesson->slug = \Str::slug($request->title);
        $lesson->storage = $request->source;
        $lesson->file_path = $request->filled('file') ? $request->file : $request->url;
        $lesson->file_type = $request->file_type;
        $lesson->duration = $request->duration;
        $lesson->is_preview = $request->filled('is_preview') ? 1 : 0;
        $lesson->downloadable = $request->filled('downloadable') ? 1 : 0;
        $lesson->description = $request->description;
        $lesson->instructor_id = Auth::user()->id;
        $lesson->course_id = $request->course_id;
        $lesson->chapter_id = $request->chapter_id;
        $lesson->order = CourseChapterLession::where('chapter_id', $request->chapter_id)->count() + 1;
        $lesson->save();
        notyf()->success('Created Successfully');
        return redirect()->back();
    }

    public function editLesson(Request $request): string
    {
        $editMode = true;
        $courseId = $request->course_id;
        $chapterId = $request->chapter_id;
        $lessonId = $request->lesson_id;
        $lesson = CourseChapterLession::where([
            'id' => $lessonId,
            'chapter_id' => $chapterId,
            'course_id' => $courseId,
        ])->first();
        return view('admin.course.contents-v2.partials.lesson-modal', compact('courseId', 'chapterId', 'lesson', 'editMode'))->render();
    }

    public function updateLesson(Request $request, string $id): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string'],
            'file_type' => ['required', 'in:video,audio,file,pdf,doc'],
            'duration' => ['required'],
            'is_preview' => ['nullable', 'boolean'],
            'downloadable' => ['nullable', 'boolean'],
            'description' => ['required']
        ];
        if ($request->filled('file')) { $rules['file'] = ['required']; } else { $rules['url'] = ['required']; }
        $request->validate($rules);
        $lesson = CourseChapterLession::findOrFail($id);
        $lesson->title = $request->title;
        $lesson->slug = \Str::slug($request->title);
        $lesson->storage = $request->source;
        $lesson->file_path = $request->filled('file') ? $request->file : $request->url;
        $lesson->file_type = $request->file_type;
        $lesson->duration = $request->duration;
        $lesson->is_preview = $request->filled('is_preview') ? 1 : 0;
        $lesson->downloadable = $request->filled('downloadable') ? 1 : 0;
        $lesson->description = $request->description;
        $lesson->instructor_id = Auth::user()->id;
        $lesson->course_id = $request->course_id;
        $lesson->chapter_id = $request->chapter_id;
        $lesson->save();
        notyf()->success('Updated Successfully');
        return redirect()->back();
    }

    public function destroyLesson($locale, string $id): Response
    {
        try {
            $lesson = CourseChapterLession::findOrFail($id);
            $lesson->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            return response(['message' => 'Something went wrong!'], 500);
        }
    }

    public function sortLesson(Request $request, $locale, string $id): Response
    {
        $newOrders = $request->order_ids;
        foreach ($newOrders as $key => $itemId) {
            $lesson = CourseChapterLession::where(['chapter_id' => $id, 'id' => $itemId])->first();
            $lesson->order = $key + 1;
            $lesson->save();
        }
        return response(['status' => 'success', 'message' => 'Updated Successfully!']);
    }

    public function sortChapter($locale, string $id): string
    {
        $chapters = CourseChapter::where('course_id', $id)->orderBy('order')->get();
        return view('admin.course.contents-v2.partials.sort-chapters-modal', compact('chapters'))->render();
    }

    public function updateSortChapter(Request $request, $locale, string $id): Response
    {
        $newOrders = $request->order_ids;
        foreach ($newOrders as $key => $itemId) {
            $lesson = CourseChapter::where(['course_id' => $id, 'id' => $itemId])->first();
            $lesson->order = $key + 1;
            $lesson->save();
        }
        return response(['status' => 'success', 'message' => 'Updated Successfully!']);
    }
}


