<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUsSection;
use App\Models\BecomeInstructorSection;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Counter;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CustomPage;
use App\Models\Feature;
use App\Models\FeaturedInstructor;
use App\Models\Hero;
use App\Models\LatestCourseSection;
use App\Models\Newsletter;
use App\Models\Testimonial;
use App\Models\VideoSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Document;
use App\Models\Service;
class FrontendController extends Controller
{
   function index() : View {
      $hero = Hero::first();
      $feature = Feature::first();
      $featuredCategories = CourseCategory::withCount(['subCategories as active_course_count' => function($query) {
         $query->whereHas('courses', function($query) {
            $query->where(['is_approved' => 'approved', 'status' => 'active']);
         });
      }])->where(['parent_id' => null, 'show_at_trending' => 1])->limit(12)->get();

      $about = AboutUsSection::first();
       
      $latestCourses = LatestCourseSection::first();
      $becomeInstructorBanner = BecomeInstructorSection::first();
      $video = VideoSection::first();
      $brands = Brand::where('status', 1)->get();
      $featuredInstructor = FeaturedInstructor::first();
      $featuredInstructorCourses = Course::whereIn('id', json_decode($featuredInstructor?->featured_courses ?? '[]'))->get();
      $testimonials = Testimonial::with('translations')->get();
      $blogs = Blog::where('status', 1)->latest()->limit(3)->get();
      $services = Service::with('translations')->where('status', true)->orderBy('display_order')->limit(8)->get();
     
    return view('frontend.pages.home.index', compact(
      'hero',
      'feature',
      'featuredCategories',
      'about',
      'latestCourses',
      'becomeInstructorBanner',
      'video',
      'brands',
      'featuredInstructor',
      'featuredInstructorCourses',
      'testimonials',
      'blogs',
      'services',
  
   ));

   } 


   function about() : View
   {
      $about = AboutUsSection::first();
      $counter = Counter::first();
      $testimonials = Testimonial::with('translations')->get();
      $blogs = Blog::where('status', 1)->latest()->limit(8)->get();

      return view('frontend.pages.about', compact('about', 'counter', 'testimonials', 'blogs'));
   }

   function documents() : View
   {
      $locale = app()->getLocale();
      $documents = \App\Models\Document::where(['locale' => $locale, 'status' => true])
         ->orderBy('published_at', 'desc')
         ->get()
         ->groupBy('category');
      return view('frontend.pages.documents', compact('documents'));
   }

   function services() : View
   {
      $services = Service::with('translations')->where('status', true)->orderBy('display_order')->get();
      return view('frontend.pages.services', compact('services'));
   }

   function applicationForm() : View
   {
      // Используем новые модели Application для справочников
      $orgTypes = \App\Models\Application\ApplicationOrgType::with('translations')->get()->map(fn($o) => [
          'id' => $o->id, 'name' => $o->name
      ]);

      $degrees = \App\Models\Application\ApplicationDegree::with('translations')->get()->map(fn($d) => [
          'id' => $d->id, 'name' => $d->name
      ]);

      $courseLangs = \App\Models\Application\ApplicationCourseLanguage::with('translations')->get()->map(fn($l) => [
          'id' => $l->id, 'name' => $l->name, 'code' => $l->code
      ]);

      // Страны и города (по умолчанию KZ)
      $countries = \App\Models\Application\ApplicationCountry::with('translations')->get()->map(fn($c) => [
          'id' => $c->id, 'code' => $c->code, 'name' => $c->name
      ]);
      $countryKz = \App\Models\Application\ApplicationCountry::where('code', 'KZ')->first();
      $cities = $countryKz
          ? \App\Models\Application\ApplicationCity::with('translations')->where('country_id', $countryKz->id)->get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])
          : collect();

      // Факультеты (для начальной загрузки)
      $faculties = \App\Models\Application\ApplicationFaculty::with('translations')->get()->map(fn($f) => [
          'id' => $f->id, 'name' => $f->name
      ]);

      // Курсы для анкет (только те, что доступны для анкет)
      $courses = \App\Models\Course::where('is_available_for_applications', true)
                                   ->where('is_approved', 'approved')
                                   ->where('status', 'active')
                                   ->with('translations')
                                   ->get()->map(fn($c) => [
                                       'id' => $c->id, 
                                       'name' => $c->translated_title ?? $c->title
                                   ]);

      return view('frontend.application-form.index', compact('orgTypes', 'degrees', 'courseLangs', 'countries', 'cities', 'faculties', 'courses'));
   }

   function applicationFormStore(Request $request) : RedirectResponse
   {
      // Определяем текущий язык
      $locale = app()->getLocale();
      
      // Базовые правила валидации
      $rules = [
          // Имя на текущем языке (обязательно)
          "last_name_{$locale}"     => ['required', 'string', 'max:255'],
          "first_name_{$locale}"    => ['required', 'string', 'max:255'],
          "middle_name_{$locale}"   => ['nullable', 'string', 'max:255'],
          
          // Имя на других языках (необязательно)
          'last_name_ru'            => ['nullable', 'string', 'max:255'],
          'first_name_ru'           => ['nullable', 'string', 'max:255'],
          'middle_name_ru'          => ['nullable', 'string', 'max:255'],
          'last_name_kk'            => ['nullable', 'string', 'max:255'],
          'first_name_kk'           => ['nullable', 'string', 'max:255'],
          'middle_name_kk'          => ['nullable', 'string', 'max:255'],
          'last_name_en'            => ['nullable', 'string', 'max:255'],
          'first_name_en'           => ['nullable', 'string', 'max:255'],
          'middle_name_en'          => ['nullable', 'string', 'max:255'],
          
          'is_foreign'              => ['required', 'boolean'],

          'faculty_id'              => ['nullable', 'exists:application_faculties,id'],
          'specialty_id'            => ['nullable', 'exists:application_specialties,id'],
          'course_id'               => ['nullable', 'exists:courses,id'],
          'custom_course_name'      => ['nullable', 'string', 'max:1000'],

          'course_language_id'      => ['required', 'exists:application_course_languages,id'],

          'workplace'               => ['nullable', 'string', 'max:255'],
          'org_type_id'             => ['nullable', 'exists:application_org_types,id'],
         // 'is_unemployed'           => ['required', 'boolean'],

          'country_id'              => ['nullable', 'exists:application_countries,id'],
          'city_id'                 => ['nullable', 'exists:application_cities,id'],
          'address_line'            => ['nullable', 'string', 'max:255'],

          'degree_id'               => ['nullable', 'exists:application_degrees,id'],
          'position'                => ['nullable', 'string', 'max:255'],
          'subjects'                => ['nullable', 'string'],

          'email'                   => ['required', 'email', 'max:255'],
          'phone'                   => ['required', 'string', 'max:32'],
      ];

      // Поле is_foreign - просто флаг true/false, не влияет на валидацию других полей
      
      // Валидация
      $request->validate($rules);

      $data = $request->all();

      // Заполняем ФИО для остальных языков, если БД требует NOT NULL
      $currentLocale = $locale; // уже определён выше
      $locales = ['ru','kk','en'];
      foreach ($locales as $lc) {
          // Фамилия
          $lnKey = "last_name_{$lc}";
          if (!isset($data[$lnKey]) || $data[$lnKey] === null || $data[$lnKey] === '') {
              $data[$lnKey] = $data["last_name_{$currentLocale}"] ?? '';
          }
          // Имя
          $fnKey = "first_name_{$lc}";
          if (!isset($data[$fnKey]) || $data[$fnKey] === null || $data[$fnKey] === '') {
              $data[$fnKey] = $data["first_name_{$currentLocale}"] ?? '';
          }
          // Отчество
          $mnKey = "middle_name_{$lc}";
          if (!isset($data[$mnKey]) || $data[$mnKey] === null) {
              $data[$mnKey] = '';
          }
      }

      // Если отмечено "Не работаю" — очищаем workplace/org_type_id
      if (!empty($data['is_unemployed'])) {
         // $data['workplace'] = null;
         // $data['org_type_id'] = null;
      }

      // Обработка курсов - если выбран курс из списка, очищаем кастомный
      if (!empty($data['course_id'])) {
          $data['custom_course_name'] = null;
      }

      // НЕ копируем ФИО автоматически - пользователь сам решает, что заполнять

      // Создаем анкету через новую модель
      $application = \App\Models\Application\Application::create($data);

      return redirect()->back()->with('success', 'Заявка успешно отправлена!');
   }


   function subscribe(Request $request) : Response {
      $request->validate([
         'email' => 'required|email|unique:newsletters,email'
      ],[
         'email.required' => 'Email is required',
         'email.email' => 'Email is invalid',
         'email.unique' => 'Email is already subscribed'
      ]);

      $newsletter = new Newsletter();
      $newsletter->email = $request->email;
      $newsletter->save();

      return response(['status' => 'success', 'message' => 'Successfully subscribed!']);
   }

   function customPage(string $slug) : View {
      $page = CustomPage::where('slug', $slug)->where('status', 1)->firstOrFail();
      return view('frontend.pages.custom-page', compact('page'));
   }
}
