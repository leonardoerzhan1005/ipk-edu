<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Application\ApplicationCourseLanguage;
use App\Models\Application\ApplicationOrgType;
use App\Models\Application\ApplicationDegree;
use App\Models\Application\ApplicationCountry;
use App\Models\Application\ApplicationCity;
use App\Models\CourseSession;
use Illuminate\Http\Request;

class CourseApplicationModalController extends Controller
{
    public function show(Request $request, string $locale, Course $course)
    {
        $courseLanguages = ApplicationCourseLanguage::with('translations')->get()->map(fn($l)=> (object)['id'=>$l->id,'name'=>$l->name]);
        $orgTypes = ApplicationOrgType::with('translations')->get()->map(fn($o)=> (object)['id'=>$o->id,'name'=>$o->name]);
        $degrees = ApplicationDegree::with('translations')->get()->map(fn($d)=> (object)['id'=>$d->id,'name'=>$d->name]);
        $countries = ApplicationCountry::with('translations')->get()->map(fn($c)=> (object)['id'=>$c->id,'code'=>$c->code,'name'=>$c->name]);
        $countryKz = ApplicationCountry::where('code','KZ')->first();
        $cities = $countryKz
            ? ApplicationCity::with('translations')->where('country_id',$countryKz->id)->get()->map(fn($c)=> (object)['id'=>$c->id,'name'=>$c->name])
            : collect();

        $sessions = CourseSession::where('course_id', $course->id)
            ->where('is_active', true)
            ->orderBy('start_date')
            ->get();

        return view('frontend.modals.course-application', compact(
            'course', 'courseLanguages', 'orgTypes', 'degrees', 'countries', 'cities', 'sessions'
        ));
    }
}


