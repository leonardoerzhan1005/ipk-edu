<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application\ApplicationOrgType;
use App\Models\Application\ApplicationOrgTypeTranslation;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationOrgTypeController extends Controller
{
    public function index() : View
    {
        $orgTypes = ApplicationOrgType::with('translations')->paginate(15);
        return view('admin.application.org-types.index', compact('orgTypes'));
    }

    public function create() : View
    {
        return view('admin.application.org-types.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'slug' => ['nullable', 'max:255', 'unique:application_org_types,slug'],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));

        $orgType = ApplicationOrgType::create(['slug' => $slug]);

        foreach (['ru','kk','en'] as $locale) {
            $name = data_get($request->input('translations'), "$locale.name");
            if ($name) {
                ApplicationOrgTypeTranslation::updateOrCreate(
                    ['org_type_id' => $orgType->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Created Successfully!');
        return to_route('admin.application-org-types.index', ['locale' => $request->route('locale')]);
    }

    public function edit(string $locale, ApplicationOrgType $application_org_type) : View
    {
        $orgType = $application_org_type->load('translations');
        return view('admin.application.org-types.edit', compact('orgType'));
    }

    public function update(Request $request, string $locale, ApplicationOrgType $application_org_type) : RedirectResponse
    {
        $request->validate([
            'slug' => ['required', 'max:255', 'unique:application_org_types,slug,' . $application_org_type->id],
            'translations.ru.name' => ['required', 'max:255'],
            'translations.kk.name' => ['nullable', 'max:255'],
            'translations.en.name' => ['nullable', 'max:255'],
        ]);

        $application_org_type->slug = $request->slug ?: Str::slug(data_get($request->input('translations'), 'ru.name'));
        $application_org_type->save();

        foreach (['ru','kk','en'] as $loc) {
            $name = data_get($request->input('translations'), "$loc.name");
            if ($name) {
                ApplicationOrgTypeTranslation::updateOrCreate(
                    ['org_type_id' => $application_org_type->id, 'locale' => $loc],
                    ['name' => $name]
                );
            }
        }

        notyf()->success('Updated Successfully!');
        return to_route('admin.application-org-types.index', ['locale' => $request->route('locale')]);
    }

    public function destroy(string $locale, ApplicationOrgType $application_org_type)
    {
        try {
            $application_org_type->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Application OrgType Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }
}


