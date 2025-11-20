<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationFaculty;
use App\Models\Application\ApplicationSpecialty;
use App\Models\Application\ApplicationSpecialtyTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationSpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $specialties = ApplicationSpecialty::with(['faculty', 'translations'])->paginate(15);
        return view('admin.application.specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $faculties = ApplicationFaculty::with('translations')->get();
        return view('admin.application.specialties.create', compact('faculties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'faculty_id' => ['nullable', 'exists:application_faculties,id'],
            'slug' => ['nullable', 'max:255', 'unique:application_specialties,slug'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));

        $specialty = ApplicationSpecialty::create([
            'faculty_id' => $request->faculty_id,
            'slug' => $slug,
        ]);

        foreach (['ru', 'kk', 'en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationSpecialtyTranslation::updateOrCreate(
                    ['specialty_id' => $specialty->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');

        return to_route('admin.application-specialties.index', ['locale' => $request->route('locale')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, ApplicationSpecialty $application_specialty) : View
    {
        $specialty = $application_specialty->load(['translations', 'faculty']);
        $faculties = ApplicationFaculty::with('translations')->get();
        return view('admin.application.specialties.edit', compact('specialty', 'faculties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $locale, ApplicationSpecialty $application_specialty) : RedirectResponse
    {
        $request->validate([
            'faculty_id' => ['nullable', 'exists:application_faculties,id'],
            'slug' => ['required', 'max:255', 'unique:application_specialties,slug,' . $application_specialty->id],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_specialty->faculty_id = $request->faculty_id;
        $application_specialty->slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));
        $application_specialty->save();

        foreach (['ru', 'kk', 'en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationSpecialtyTranslation::updateOrCreate(
                    ['specialty_id' => $application_specialty->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');

        return to_route('admin.application-specialties.index', ['locale' => $request->route('locale')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, ApplicationSpecialty $application_specialty)
    {
        try {
            $application_specialty->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application Specialty Error >> " . $e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


