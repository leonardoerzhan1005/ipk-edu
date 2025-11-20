<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Http\Request;

class CourseSessionController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->get("course_id");
        $courses = Course::with("translations")->orderBy("title")->get();
        $sessions = CourseSession::with(["course.translations", "translations"])
            ->when($courseId, fn($q) => $q->where("course_id", $courseId))
            ->orderBy("order", "asc")
            ->orderByDesc("start_date")
            ->paginate(20)
            ->withQueryString();

        return view(
            "admin.course-sessions.index",
            compact("sessions", "courses", "courseId"),
        );
    }

    public function create(Request $request)
    {
        $courses = Course::with("translations")->orderBy("title")->get();
        $prefCourseId = $request->get("course_id");
        return view("admin.course-sessions.form", [
            "session" => new CourseSession(),
            "courses" => $courses,
            "prefCourseId" => $prefCourseId,
            "method" => "POST",
            "action" => route("admin.course-sessions.store", [
                "locale" => app()->getLocale(),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "course_id" => ["required", "exists:courses,id"],
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date", "after_or_equal:start_date"],
                "format" => ["required", "in:online,offline,hybrid"],
                "order" => ["nullable", "integer"],
                "is_active" => ["nullable", "boolean"],
                "translations.ru.description" => ["nullable", "string"],
            ]);

            // Создаём сессию курса
            $session = new CourseSession();
            $session->course_id = $request->course_id;
            $session->start_date = $request->start_date;
            $session->end_date = $request->end_date;
            $session->format = $request->format;
            $session->order = $request->order ?? 0;
            $session->is_active = $request->is_active ?? 1;
            $session->save();

            // Сохраняем переводы
            foreach (["ru", "kk", "en"] as $lang) {
                $description = $request->input(
                    "translations.{$lang}.description",
                );

                if ($lang === "ru") {
                    // Русский обязателен (если заполнен)
                    if ($description) {
                        $session->translations()->create([
                            "locale" => $lang,
                            "description" => $description,
                        ]);
                    }
                } else {
                    // Другие языки опциональны
                    if ($description) {
                        $session->translations()->create([
                            "locale" => $lang,
                            "description" => $description,
                        ]);
                    }
                }
            }

            notyf()->success("Создано успешно!");
            return redirect()->route("admin.course-sessions.index", [
                "locale" => app()->getLocale(),
                "course_id" => $request->course_id,
            ]);
        } catch (\Exception $e) {
            \Log::error("Course Session creation failed: " . $e->getMessage());
            notyf()->error("Ошибка при создании: " . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(string $locale, string $course_session)
    {
        $session = CourseSession::with("translations")->findOrFail(
            $course_session,
        );
        $courses = Course::with("translations")->orderBy("title")->get();
        return view("admin.course-sessions.form", [
            "session" => $session,
            "courses" => $courses,
            "prefCourseId" => $session->course_id,
            "method" => "POST",
            "action" => route("admin.course-sessions.update", [
                "locale" => $locale,
                "course_session" => $session->id,
            ]),
        ]);
    }

    public function update(
        Request $request,
        string $locale,
        string $course_session,
    ) {
        try {
            $request->validate([
                "course_id" => ["required", "exists:courses,id"],
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date", "after_or_equal:start_date"],
                "format" => ["required", "in:online,offline,hybrid"],
                "order" => ["nullable", "integer"],
                "is_active" => ["nullable", "boolean"],
                "translations.ru.description" => ["nullable", "string"],
            ]);

            $session = CourseSession::findOrFail($course_session);
            $session->course_id = $request->course_id;
            $session->start_date = $request->start_date;
            $session->end_date = $request->end_date;
            $session->format = $request->format;
            $session->order = $request->order ?? 0;
            $session->is_active = $request->is_active ?? 0;
            $session->save();

            // Обновляем переводы
            foreach (["ru", "kk", "en"] as $lang) {
                $description = $request->input(
                    "translations.{$lang}.description",
                );

                if ($lang === "ru") {
                    // Русский обязателен (если заполнен)
                    if ($description) {
                        $session
                            ->translations()
                            ->updateOrCreate(
                                ["locale" => $lang],
                                ["description" => $description],
                            );
                    }
                } else {
                    // Другие языки опциональны
                    if ($description) {
                        $session
                            ->translations()
                            ->updateOrCreate(
                                ["locale" => $lang],
                                ["description" => $description],
                            );
                    } else {
                        // Удаляем перевод, если поле пустое
                        $session
                            ->translations()
                            ->where("locale", $lang)
                            ->delete();
                    }
                }
            }

            notyf()->success("Обновлено успешно!");
            return redirect()->route("admin.course-sessions.index", [
                "locale" => $locale,
                "course_id" => $request->course_id,
            ]);
        } catch (\Exception $e) {
            \Log::error("Course Session update failed: " . $e->getMessage());
            notyf()->error("Ошибка при обновлении: " . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(string $locale, string $course_session)
    {
        try {
            $session = CourseSession::findOrFail($course_session);
            $courseId = $session->course_id;
            $session->delete();
            notyf()->success("Удалено успешно!");
            return redirect()->route("admin.course-sessions.index", [
                "locale" => $locale,
                "course_id" => $courseId,
            ]);
        } catch (\Exception $e) {
            \Log::error("Course Session delete failed: " . $e->getMessage());
            notyf()->error("Ошибка при удалении");
            return back();
        }
    }
}
