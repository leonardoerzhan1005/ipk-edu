<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Models\Application\{
    Application, ApplicationFaculty, ApplicationSpecialty, ApplicationCourse,
    ApplicationCountry, ApplicationCity, ApplicationOrgType, ApplicationDegree, ApplicationCourseLanguage
};
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Показать форму создания анкеты
     */
    public function create()
    {
        // Базовые справочники можно отдать сразу
        $orgTypes = ApplicationOrgType::with('translations')->get()->map(fn($o) => [
            'id' => $o->id, 'name' => $o->name
        ]);

        $degrees = ApplicationDegree::with('translations')->get()->map(fn($d) => [
            'id' => $d->id, 'name' => $d->name
        ]);

        $courseLangs = ApplicationCourseLanguage::with('translations')->get()->map(fn($l) => [
            'id' => $l->id, 'name' => $l->name, 'code' => $l->code
        ]);

        // Казахстан и его города
        $countryKz = ApplicationCountry::where('code', 'KZ')->first();
        $cities = $countryKz
            ? ApplicationCity::with('translations')->where('country_id', $countryKz->id)->get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])
            : collect();

        // Факультеты/спец-сти/курсы лучше получать Ajax-ом (динамические зависимости)
        return view('applications.create', compact('orgTypes', 'degrees', 'courseLangs', 'cities'));
    }

    /**
     * Сохранить анкету
     */
    public function store(StoreApplicationRequest $request)
    {
        $data = $request->validated();

        // Если отмечено "Не работаю" — очищаем workplace/org_type_id
       // if (!empty($data['is_unemployed'])) {
         //   $data['workplace'] = null;
           // $data['org_type_id'] = null;
        //}

        $application = Application::create($data);

        // Возврат JSON (если форма SPA) или редирект
        if ($request->wantsJson()) {
            return response()->json(['id' => $application->id, 'message' => 'Заявка сохранена'], 201);
        }

        return redirect()->back()->with('status', 'Заявка сохранена');
    }
}
