<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationCountry;
use App\Models\Application\ApplicationCountryTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationCountryController extends Controller
{
    public function index() : View
    {
        $countries = ApplicationCountry::with('translations')->paginate(15);
        return view('admin.application.countries.index', compact('countries'));
    }

    public function create() : View
    {
        return view('admin.application.countries.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'max:5', 'unique:application_countries,code'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $code = strtoupper(trim($request->code));

        $country = ApplicationCountry::create(['code' => $code]);

        foreach (['ru','kk','en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationCountryTranslation::updateOrCreate(
                    ['country_id' => $country->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.application-countries.index', ['locale' => $request->route('locale')]);
    }

    public function edit(string $locale, ApplicationCountry $application_country) : View
    {
        $country = $application_country->load('translations');
        return view('admin.application.countries.edit', compact('country'));
    }

    public function update(Request $request, string $locale, ApplicationCountry $application_country) : RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'max:5', 'unique:application_countries,code,' . $application_country->id],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_country->code = strtoupper(trim($request->code));
        $application_country->save();

        foreach (['ru','kk','en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationCountryTranslation::updateOrCreate(
                    ['country_id' => $application_country->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.application-countries.index', ['locale' => $request->route('locale')]);
    }

    public function destroy(string $locale, ApplicationCountry $application_country)
    {
        try {
            $application_country->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application Country Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


