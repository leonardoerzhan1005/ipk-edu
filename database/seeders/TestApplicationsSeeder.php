<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application\{
    Application, ApplicationCountry, ApplicationCity, ApplicationOrgType, 
    ApplicationDegree, ApplicationCourseLanguage,
    ApplicationFaculty, ApplicationSpecialty
};
use App\Models\Course;
use App\Models\User;

class TestApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем существующие данные
        $country = ApplicationCountry::first();
        $city = ApplicationCity::first();
        $orgType = ApplicationOrgType::first();
        $degree = ApplicationDegree::first();
        $courseLanguage = ApplicationCourseLanguage::first();
        $faculty = ApplicationFaculty::first();
        $specialty = ApplicationSpecialty::first();
        $course = Course::first();
        $user = User::first();

        // Создаем тестовые заявки
        $applications = [
            [
                'last_name_ru' => 'Иванов',
                'first_name_ru' => 'Иван',
                'middle_name_ru' => 'Иванович',
                'last_name_kk' => 'Иванов',
                'first_name_kk' => 'Иван',
                'middle_name_kk' => 'Иванович',
                'last_name_en' => 'Ivanov',
                'first_name_en' => 'Ivan',
                'middle_name_en' => 'Ivanovich',
                'is_foreign' => false,
                'faculty_id' => $faculty ? $faculty->id : null,
                'specialty_id' => $specialty ? $specialty->id : null,
                'course_id' => $course ? $course->id : null,
                'course_language_id' => $courseLanguage ? $courseLanguage->id : null,
                'workplace' => 'Школа №1',
                'org_type_id' => $orgType ? $orgType->id : null,
                'is_unemployed' => false,
                'country_id' => $country ? $country->id : null,
                'city_id' => $city ? $city->id : null,
                'address_line' => 'ул. Пушкина, д. 10',
                'degree_id' => $degree ? $degree->id : null,
                'position' => 'Учитель',
                'subjects' => 'Математика, Физика',
                'email' => 'ivanov@example.com',
                'phone' => '+7 777 123 45 67',
                'user_id' => $user ? $user->id : null,
                'status' => 'pending'
            ],
            [
                'last_name_ru' => 'Петрова',
                'first_name_ru' => 'Анна',
                'middle_name_ru' => 'Сергеевна',
                'last_name_kk' => 'Петрова',
                'first_name_kk' => 'Анна',
                'middle_name_kk' => 'Сергеевна',
                'last_name_en' => 'Petrova',
                'first_name_en' => 'Anna',
                'middle_name_en' => 'Sergeevna',
                'is_foreign' => false,
                'faculty_id' => $faculty ? $faculty->id : null,
                'specialty_id' => $specialty ? $specialty->id : null,
                'course_id' => $course ? $course->id : null,
                'course_language_id' => $courseLanguage ? $courseLanguage->id : null,
                'workplace' => 'Колледж',
                'org_type_id' => $orgType ? $orgType->id : null,
                'is_unemployed' => false,
                'country_id' => $country ? $country->id : null,
                'city_id' => $city ? $city->id : null,
                'address_line' => 'ул. Ленина, д. 25',
                'degree_id' => $degree ? $degree->id : null,
                'position' => 'Преподаватель',
                'subjects' => 'История, Обществознание',
                'email' => 'petrova@example.com',
                'phone' => '+7 777 234 56 78',
                'user_id' => $user ? $user->id : null,
                'status' => 'approved'
            ],
            [
                'last_name_ru' => 'Смирнов',
                'first_name_ru' => 'Алексей',
                'middle_name_ru' => 'Петрович',
                'last_name_kk' => 'Смирнов',
                'first_name_kk' => 'Алексей',
                'middle_name_kk' => 'Петрович',
                'last_name_en' => 'Smirnov',
                'first_name_en' => 'Alexey',
                'middle_name_en' => 'Petrovich',
                'is_foreign' => false,
                'faculty_id' => $faculty ? $faculty->id : null,
                'specialty_id' => $specialty ? $specialty->id : null,
                'course_id' => $course ? $course->id : null,
                'course_language_id' => $courseLanguage ? $courseLanguage->id : null,
                'workplace' => null,
                'org_type_id' => null,
                'is_unemployed' => true,
                'country_id' => $country ? $country->id : null,
                'city_id' => $city ? $city->id : null,
                'address_line' => 'ул. Мира, д. 15',
                'degree_id' => $degree ? $degree->id : null,
                'position' => null,
                'subjects' => 'Программирование, Веб-разработка',
                'email' => 'smirnov@example.com',
                'phone' => '+7 777 345 67 89',
                'user_id' => $user ? $user->id : null,
                'status' => 'pending'
            ]
        ];

        foreach ($applications as $appData) {
            Application::create($appData);
        }
    }
}
