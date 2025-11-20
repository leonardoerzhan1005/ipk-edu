<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application\{ApplicationDegree, ApplicationDegreeTranslation};

class ApplicationDegreeListSeeder extends Seeder
{
    public function run(): void
    {
        $degrees = [
            // slug => [ru, kk, en]
            'bachelor' => ['Бакалавр', 'Бакалавр', 'Bachelor'],
            'master' => ['Магистр', 'Магистр', 'Master'],
            'phd' => ['Доктор PhD', 'PhD докторы', 'PhD'],
            'doctor_of_science' => ['Доктор наук', 'Ғылым докторы', 'Doctor of Science'],
            'candidate' => ['Кандидат наук', 'Ғылым кандидаты', 'Candidate of Science'],
            'none' => ['Без учёной степени', 'Ғылыми дәрежесі жоқ', 'No academic degree'],
        ];

        $created = 0;
        foreach ($degrees as $slug => [$ru, $kk, $en]) {
            $degree = ApplicationDegree::firstOrCreate(['slug' => $slug]);
            ApplicationDegreeTranslation::updateOrCreate(
                ['degree_id' => $degree->id, 'locale' => 'ru'],
                ['name' => $ru]
            );
            ApplicationDegreeTranslation::updateOrCreate(
                ['degree_id' => $degree->id, 'locale' => 'kk'],
                ['name' => $kk]
            );
            ApplicationDegreeTranslation::updateOrCreate(
                ['degree_id' => $degree->id, 'locale' => 'en'],
                ['name' => $en]
            );
            if ($degree->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command?->info("Academic degrees upserted. New created: {$created}");
    }
}


