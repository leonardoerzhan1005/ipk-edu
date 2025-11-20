<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseBasicInfoCreateRequest;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseChapter;
use App\Models\CourseTranslation;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;
use App\Models\User;
use App\Traits\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    use FileUpload;

    function index(): View
    {
        $courses = Course::with(["instructor"])->paginate(25);
        return view("admin.course.course-module.index", compact("courses"));
    }

    /** change approve status */
    function updateApproval(Request $request, Course $course): Response
    {
        $course->is_approved = $request->status;
        $course->save();

        return response([
            "status" => "success",
            "message" => "Updated successfully.",
        ]);
    }

    function create(): View
    {
        $instructors = User::where("role", "instructor")
            ->where("approve_status", "approved")
            ->get();
        return view(
            "admin.course.course-module.create",
            compact("instructors"),
        );
    }

    function storeBasicInfo(CourseBasicInfoCreateRequest $request)
    {
        $thumbnailPath = $this->uploadFile($request->file("thumbnail"));
        $course = new Course();
        $course->title = $request->title;
        $course->slug = \Str::slug($request->title);
        $course->seo_description = $request->seo_description;
        $course->thumbnail = $thumbnailPath;
        $course->demo_video_storage = $request->demo_video_storage;
        $course->demo_video_source = $request->demo_video_source;
        $course->price = $request->price;
        $course->discount = $request->discount;
        $course->description = $request->description;
        $course->instructor_id = $request->instructor;
        $course->save();

        // save translations if provided
        $translations = $request->input("translations", []);
        foreach (["ru", "kk", "en"] as $locale) {
            if (!isset($translations[$locale])) {
                continue;
            }
            $payload = $translations[$locale];
            if (
                ($payload["title"] ?? null) ||
                ($payload["description"] ?? null) ||
                ($payload["seo_description"] ?? null) ||
                ($payload["slug"] ?? null)
            ) {
                CourseTranslation::updateOrCreate(
                    ["course_id" => $course->id, "locale" => $locale],
                    [
                        "title" => $payload["title"] ?? $course->title,
                        "slug" =>
                            $payload["slug"] ??
                            \Str::slug($payload["title"] ?? $course->title),
                        "description" =>
                            $payload["description"] ?? $course->description,
                        "seo_description" =>
                            $payload["seo_description"] ??
                            $course->seo_description,
                    ],
                );
            }
        }

        // save course id on session
        Session::put("course_create_id", $course->id);

        $locale = $request->route("locale");
        return response([
            "status" => "success",
            "message" => "Updated successfully.",
            "redirect" => route("admin.courses.edit", [
                "locale" => $locale,
                "id" => $course->id,
                "step" => $request->next_step,
            ]),
        ]);
    }

    function edit(Request $request)
    {
        $id = (int) ($request->route("id") ?? $request->input("id"));
        $step = (string) $request->query("step", "1");

        switch ($step) {
            case "1":
                $course = Course::with("translations")->findOrFail($id);
                return view(
                    "admin.course.course-module.edit",
                    compact("course"),
                );
                break;

            case "2":
                $categories = CourseCategory::where("status", 1)
                    ->whereNull("parent_id")
                    ->with("subCategories")
                    ->get();
                $levels = CourseLevel::all();
                $languages = CourseLanguage::all();
                $course = Course::findOrFail($id);
                return view(
                    "admin.course.course-module.more-info",
                    compact("categories", "levels", "languages", "course"),
                );
                break;

            case "3":
                $courseId = $id;
                $chapters = CourseChapter::where(["course_id" => $courseId])
                    ->orderBy("order")
                    ->get();
                return view(
                    "admin.course.course-module.course-content",
                    compact("courseId", "chapters"),
                );
                break;

            case "4":
                $courseId = $id;
                $course = Course::findOrFail($id);
                $editMode = true;
                return view(
                    "admin.course.course-module.finish",
                    compact("course", "editMode"),
                );
                break;
        }

        // Fallback to step 1 if an unexpected step value is provided
        $locale = $request->route("locale");
        return redirect()->route("admin.courses.edit", [
            "locale" => $locale,
            "id" => $id,
            "step" => "1",
        ]);
    }

    function update(Request $request)
    {
        // dd($request->all());
        switch ($request->current_step) {
            case "1":
                $rules = [
                    "title" => ["required", "max:255", "string"],
                    "seo_description" => ["nullable", "max:255", "string"],
                    "demo_video_storage" => [
                        "nullable",
                        "in:youtube,vimeo,external_link,upload",
                        "string",
                    ],
                    "price" => ["required", "numeric"],
                    "discount" => ["nullable", "numeric"],
                    "description" => ["required"],
                    "thumbnail" => ["nullable", "image", "max:3000"],
                    "demo_video_source" => ["nullable"],
                ];

                $request->validate($rules);

                $course = Course::findOrFail($request->id);

                if ($request->hasFile("thumbnail")) {
                    $thumbnailPath = $this->uploadFile(
                        $request->file("thumbnail"),
                    );
                    $this->deleteFile($course->thumbnail);
                    $course->thumbnail = $thumbnailPath;
                }

                $course->title = $request->title;
                $course->slug = \Str::slug($request->title);
                $course->seo_description = $request->seo_description;
                $course->demo_video_storage = $request->demo_video_storage;
                $course->demo_video_source = $request->filled("file")
                    ? $request->file
                    : $request->url;
                $course->price = $request->price;
                $course->discount = $request->discount;
                $course->description = $request->description;
                $course->instructor_id = $course->instructor->id;
                $course->save();

                // save translations if provided
                $translations = $request->input("translations", []);
                foreach (["ru", "kk", "en"] as $locale) {
                    if (!isset($translations[$locale])) {
                        continue;
                    }
                    $payload = $translations[$locale];
                    if (
                        ($payload["title"] ?? null) ||
                        ($payload["description"] ?? null) ||
                        ($payload["seo_description"] ?? null) ||
                        ($payload["slug"] ?? null)
                    ) {
                        CourseTranslation::updateOrCreate(
                            ["course_id" => $course->id, "locale" => $locale],
                            [
                                "title" => $payload["title"] ?? $course->title,
                                "slug" =>
                                    $payload["slug"] ??
                                    \Str::slug(
                                        $payload["title"] ?? $course->title,
                                    ),
                                "description" =>
                                    $payload["description"] ??
                                    $course->description,
                                "seo_description" =>
                                    $payload["seo_description"] ??
                                    $course->seo_description,
                            ],
                        );
                    }
                }

                // save course id on session
                Session::put("course_create_id", $course->id);

                $locale = $request->route("locale");
                return response([
                    "status" => "success",
                    "message" => "Updated successfully.",
                    "redirect" => route("admin.courses.edit", [
                        "locale" => $locale,
                        "id" => $course->id,
                        "step" => $request->next_step,
                    ]),
                ]);

                break;

            case "2":
                // validation
                $request->validate([
                    "capacity" => ["nullable", "numeric"],
                    "duration" => ["required", "numeric"],
                    "qna" => ["nullable", "boolean"],
                    "certificate" => ["nullable", "boolean"],
                    "category" => ["required", "integer"],
                    "level" => ["required", "integer"],
                    "language" => ["required", "integer"],
                    "is_available_for_applications" => ["nullable", "boolean"],
                    "faculty_id" => ["nullable", "integer"],
                    "specialty_id" => ["nullable", "integer"],
                ]);

                // update course data
                $course = Course::findOrFail($request->id);
                $course->capacity = $request->capacity;
                $course->duration = $request->duration;
                $course->qna = $request->qna ? 1 : 0;
                $course->certificate = $request->certificate ? 1 : 0;
                $course->category_id = $request->category;
                $course->course_level_id = $request->level;
                $course->course_language_id = $request->language;
                $course->is_available_for_applications =
                    (bool) $request->is_available_for_applications;
                $course->faculty_id = $request->input("faculty_id") ?: null;
                $course->specialty_id = $request->input("specialty_id") ?: null;
                $course->save();

                $locale = $request->route("locale");
                return response([
                    "status" => "success",
                    "message" => "Updated successfully.",
                    "redirect" => route("admin.courses.edit", [
                        "locale" => $locale,
                        "id" => $course->id,
                        "step" => $request->next_step,
                    ]),
                ]);

                break;
            case "3":
                $locale = $request->route("locale");
                return response([
                    "status" => "success",
                    "message" => "Updated successfully.",
                    "redirect" => route("admin.courses.edit", [
                        "locale" => $locale,
                        "id" => $request->id,
                        "step" => $request->next_step,
                    ]),
                ]);
                break;

            case "4":
                // validation
                $request->validate([
                    "message" => ["nullable", "max:1000", "string"],
                    "status" => ["required", "in:active,inactive,draft"],
                ]);

                // update course data
                $course = Course::findOrFail($request->id);
                $course->message_for_reviewer = $request->message;
                $course->status = $request->status;
                $course->save();
                return response([
                    "status" => "success",
                    "message" => "Updated successfully.",
                    "redirect" => route("admin.courses.index"),
                ]);
                break;
        }
    }

    public function destroy(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()
            ->route("admin.courses.index", [
                "locale" => $request->route("locale"),
            ])
            ->with("success", "Course deleted successfully.");
    }
}
