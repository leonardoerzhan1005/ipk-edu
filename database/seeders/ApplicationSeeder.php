<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application\{
    ApplicationCountry, ApplicationCity, ApplicationOrgType, 
    ApplicationDegree, ApplicationCourseLanguage,
    ApplicationFaculty, ApplicationSpecialty
};

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Страны
        $kazakhstan = ApplicationCountry::create(['code' => 'KZ']);
        
        // Переводы для Казахстана
        $kazakhstan->translations()->createMany([
            ['locale' => 'ru', 'name' => 'Казахстан'],
            ['locale' => 'kk', 'name' => 'Қазақстан'],
            ['locale' => 'en', 'name' => 'Kazakhstan']
        ]);
        
        // 2. Города Казахстана
        $cities = [
            'almaty' => 'Алматы',
            'astana' => 'Астана', 
            'shymkent' => 'Шымкент',
            'aktobe' => 'Актобе',
            'karaganda' => 'Караганда',
            'taraz' => 'Тараз',
            'pavlodar' => 'Павлодар',
            'semey' => 'Семей',
            'oskemen' => 'Усть-Каменогорск',
            'atyrau' => 'Атырау'
        ];
        
        foreach ($cities as $slug => $name) {
            $city = ApplicationCity::create([
                'country_id' => $kazakhstan->id,
                'slug' => $slug
            ]);
            
            // Переводы для городов
            $city->translations()->createMany([
                ['locale' => 'ru', 'name' => $name],
                ['locale' => 'kk', 'name' => $name], // Можно добавить казахские названия
                ['locale' => 'en', 'name' => $name]  // Можно добавить английские названия
            ]);
        }
        
        // 3. Типы организаций
        $orgTypes = [
            'school' => 'Школа',
            'college' => 'Колледж', 
            'university' => 'ВУЗ',
            'other' => 'Другое'
        ];
        
        foreach ($orgTypes as $slug => $name) {
            $orgType = ApplicationOrgType::create(['slug' => $slug]);
            
            $orgType->translations()->createMany([
                ['locale' => 'ru', 'name' => $name],
                ['locale' => 'kk', 'name' => $name],
                ['locale' => 'en', 'name' => $name]
            ]);
        }
        
        // 4. Учёные степени
        $degrees = [
            'bachelor' => 'Бакалавр',
            'master' => 'Магистр',
            'phd' => 'Доктор наук',
            'candidate' => 'Кандидат наук',
            'none' => 'Без учёной степени'
        ];
        
        foreach ($degrees as $slug => $name) {
            $degree = ApplicationDegree::create(['slug' => $slug]);
            
            $degree->translations()->createMany([
                ['locale' => 'ru', 'name' => $name],
                ['locale' => 'kk', 'name' => $name],
                ['locale' => 'en', 'name' => $name]
            ]);
        }
        
        // 5. Языки прохождения курса
        $courseLanguages = [
            'ru' => 'Русский',
            'kk' => 'Қазақ тілі', 
            'en' => 'English'
        ];
        
        foreach ($courseLanguages as $code => $name) {
            $courseLang = ApplicationCourseLanguage::create(['code' => $code]);
            
            $courseLang->translations()->createMany([
                ['locale' => 'ru', 'name' => $name],
                ['locale' => 'kk', 'name' => $name],
                ['locale' => 'en', 'name' => $name]
            ]);
        }
        
        // 6. Факультеты (примеры)
        $faculties = [
            'engineering' => 'Инженерный факультет',
            'economics' => 'Экономический факультет',
            'medicine' => 'Медицинский факультет',
            'education' => 'Педагогический факультет',
            'law' => 'Юридический факультет'
        ];
        
        foreach ($faculties as $slug => $name) {
            $faculty = ApplicationFaculty::create(['slug' => $slug]);
            
            $faculty->translations()->createMany([
                ['locale' => 'ru', 'name' => $name],
                ['locale' => 'kk', 'name' => $name],
                ['locale' => 'en', 'name' => $name]
            ]);
        }
        
        // 7. Специальности (примеры)
        $specialties = [
            'computer_science' => ['faculty' => 'engineering', 'name' => 'Информатика'],
            'mechanical_engineering' => ['faculty' => 'engineering', 'name' => 'Механика'],
            'accounting' => ['faculty' => 'economics', 'name' => 'Бухгалтерия'],
            'management' => ['faculty' => 'economics', 'name' => 'Менеджмент'],
            'pedagogy' => ['faculty' => 'education', 'name' => 'Педагогика']
        ];
        
        foreach ($specialties as $slug => $data) {
            $faculty = ApplicationFaculty::where('slug', $data['faculty'])->first();
            $specialty = ApplicationSpecialty::create([
                'faculty_id' => $faculty->id,
                'slug' => $slug
            ]);
            
            $specialty->translations()->createMany([
                ['locale' => 'ru', 'name' => $data['name']],
                ['locale' => 'kk', 'name' => $data['name']],
                ['locale' => 'en', 'name' => $data['name']]
            ]);
        }
        
        // 8. Курсы будут создаваться через существующую систему LMS
        // и связываться с факультетами/специализациями по необходимости
        
        // Пример: если у вас уже есть курсы в базе, можно добавить переводы
        // $existingCourse = \App\Models\Course::first();
        // if ($existingCourse) {
        //     $existingCourse->translations()->createMany([
        //         ['locale' => 'ru', 'title' => 'Курс на русском', 'description' => 'Описание на русском'],
        //         ['locale' => 'kk', 'title' => 'Қазақ тіліндегі курс', 'description' => 'Қазақ тіліндегі сипаттама'],
        //         ['locale' => 'en', 'title' => 'Course in English', 'description' => 'Description in English']
        //     ]);
        // }
    }
}
