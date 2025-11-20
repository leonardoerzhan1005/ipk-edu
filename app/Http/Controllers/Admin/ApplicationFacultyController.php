<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationFaculty;
use App\Models\Application\ApplicationFacultyTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationFacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $faculties = ApplicationFaculty::with('translations')->paginate(15);
        return view('admin.application.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('admin.application.faculties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'slug' => ['nullable', 'max:255', 'unique:application_faculties,slug'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));

        $faculty = ApplicationFaculty::create(['slug' => $slug]);

        foreach (['ru', 'kk', 'en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationFacultyTranslation::updateOrCreate(
                    ['faculty_id' => $faculty->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');

        return to_route('admin.application-faculties.index', ['locale' => $request->route('locale')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, ApplicationFaculty $application_faculty) : View
    {
        $faculty = $application_faculty->load('translations');
        return view('admin.application.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $locale, ApplicationFaculty $application_faculty) : RedirectResponse
    {
        $request->validate([
            'slug' => ['required', 'max:255', 'unique:application_faculties,slug,' . $application_faculty->id],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_faculty->slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));
        $application_faculty->save();

        foreach (['ru', 'kk', 'en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationFacultyTranslation::updateOrCreate(
                    ['faculty_id' => $application_faculty->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');

        return to_route('admin.application-faculties.index', ['locale' => $request->route('locale')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, ApplicationFaculty $application_faculty)
    {
        try {
            $application_faculty->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application Faculty Error >> " . $e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


