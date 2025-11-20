<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationCity;
use App\Models\Application\ApplicationCityTranslation;
use App\Models\Application\ApplicationCountry;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationCityController extends Controller
{
    public function index() : View
    {
        $cities = ApplicationCity::with(['country','translations'])->paginate(15);
        return view('admin.application.cities.index', compact('cities'));
    }

    public function create() : View
    {
        $countries = ApplicationCountry::with('translations')->get();
        return view('admin.application.cities.create', compact('countries'));
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'country_id' => ['required', 'exists:application_countries,id'],
            'slug' => ['nullable', 'max:255'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));

        $city = ApplicationCity::create([
            'country_id' => $request->country_id,
            'slug' => $slug,
        ]);

        foreach (['ru','kk','en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationCityTranslation::updateOrCreate(
                    ['city_id' => $city->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.application-cities.index', ['locale' => $request->route('locale')]);
    }

    public function edit(string $locale, ApplicationCity $application_city) : View
    {
        $city = $application_city->load(['translations','country']);
        $countries = ApplicationCountry::with('translations')->get();
        return view('admin.application.cities.edit', compact('city','countries'));
    }

    public function update(Request $request, string $locale, ApplicationCity $application_city) : RedirectResponse
    {
        $request->validate([
            'country_id' => ['required', 'exists:application_countries,id'],
            'slug' => ['required', 'max:255'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_city->country_id = $request->country_id;
        $application_city->slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));
        $application_city->save();

        foreach (['ru','kk','en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationCityTranslation::updateOrCreate(
                    ['city_id' => $application_city->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.application-cities.index', ['locale' => $request->route('locale')]);
    }

    public function destroy(string $locale, ApplicationCity $application_city)
    {
        try {
            $application_city->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application City Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


