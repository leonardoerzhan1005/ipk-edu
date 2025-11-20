<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationDegree;
use App\Models\Application\ApplicationDegreeTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationDegreeController extends Controller
{
    public function index() : View
    {
        $degrees = ApplicationDegree::with('translations')->paginate(15);
        return view('admin.application.degrees.index', compact('degrees'));
    }

    public function create() : View
    {
        return view('admin.application.degrees.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'slug' => ['nullable', 'max:255', 'unique:application_degrees,slug'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));

        $degree = ApplicationDegree::create(['slug' => $slug]);

        foreach (['ru','kk','en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationDegreeTranslation::updateOrCreate(
                    ['degree_id' => $degree->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.application-degrees.index', ['locale' => $request->route('locale')]);
    }

    public function edit(string $locale, ApplicationDegree $application_degree) : View
    {
        $degree = $application_degree->load('translations');
        return view('admin.application.degrees.edit', compact('degree'));
    }

    public function update(Request $request, string $locale, ApplicationDegree $application_degree) : RedirectResponse
    {
        $request->validate([
            'slug' => ['required', 'max:255', 'unique:application_degrees,slug,' . $application_degree->id],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_degree->slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));
        $application_degree->save();

        foreach (['ru','kk','en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationDegreeTranslation::updateOrCreate(
                    ['degree_id' => $application_degree->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.application-degrees.index', ['locale' => $request->route('locale')]);
    }

    public function destroy(string $locale, ApplicationDegree $application_degree)
    {
        try {
            $application_degree->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application Degree Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


