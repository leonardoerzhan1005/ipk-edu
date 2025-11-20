<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationCourseLanguage;
use App\Models\Application\ApplicationCourseLanguageTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicationCourseLanguageController extends Controller
{
    public function index() : View
    {
        $courseLanguages = ApplicationCourseLanguage::with('translations')->paginate(15);
        return view('admin.application.course-languages.index', compact('courseLanguages'));
    }

    public function create() : View
    {
        return view('admin.application.course-languages.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'max:10', 'unique:application_course_languages,code'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $lang = ApplicationCourseLanguage::create(['code' => $request->code]);

        foreach (['ru','kk','en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationCourseLanguageTranslation::updateOrCreate(
                    ['course_language_id' => $lang->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.application-course-languages.index', ['locale' => $request->route('locale')]);
    }

    public function edit(string $locale, ApplicationCourseLanguage $application_course_language) : View
    {
        $courseLanguage = $application_course_language->load('translations');
        return view('admin.application.course-languages.edit', compact('courseLanguage'));
    }

    public function update(Request $request, string $locale, ApplicationCourseLanguage $application_course_language) : RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'max:10', 'unique:application_course_languages,code,' . $application_course_language->id],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_course_language->code = $request->code;
        $application_course_language->save();

        foreach (['ru','kk','en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationCourseLanguageTranslation::updateOrCreate(
                    ['course_language_id' => $application_course_language->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.application-course-languages.index', ['locale' => $request->route('locale')]);
    }

    public function destroy(string $locale, ApplicationCourseLanguage $application_course_language)
    {
        try {
            $application_course_language->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application CourseLanguage Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


