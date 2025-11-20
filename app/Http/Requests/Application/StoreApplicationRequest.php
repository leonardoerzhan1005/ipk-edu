<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name_cyr'     => ['required', 'string', 'max:255'],
            'full_name_lat'     => ['nullable', 'string', 'max:255'],
            'is_foreign'        => ['required', 'boolean'],

            'faculty_id'        => ['nullable', 'exists:application_faculties,id'],
            'specialty_id'      => ['nullable', 'exists:application_specialties,id'],
            'course_id'         => ['nullable', 'exists:application_courses,id'],

            'course_language_id'=> ['required', 'exists:application_course_languages,id'],

            'workplace'         => ['nullable', 'string', 'max:255'],
            'org_type_id'       => ['nullable', 'exists:application_org_types,id'],
            'is_unemployed'     => ['required', 'boolean'],

            'country_id'        => ['nullable', 'exists:application_countries,id'],
            'city_id'           => ['nullable', 'exists:application_cities,id'],
            'address_line'      => ['nullable', 'string', 'max:255'],

            'degree_id'         => ['nullable', 'exists:application_degrees,id'],
            'position'          => ['nullable', 'string', 'max:255'],
            'subjects'          => ['nullable', 'string'],

            'email'             => ['required', 'email', 'max:255'],
            'phone'             => ['required', 'string', 'max:32'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name_cyr.required' => 'Ф.И.О. (кириллица) обязательно для заполнения',
            'course_language_id.required' => 'Язык прохождения курса обязателен для выбора',
            'email.required' => 'E-mail обязателен для заполнения',
            'phone.required' => 'Мобильный телефон обязателен для заполнения',
        ];
    }
}
