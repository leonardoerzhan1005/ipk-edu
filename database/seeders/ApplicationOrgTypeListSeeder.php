<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application\{ApplicationOrgType, ApplicationOrgTypeTranslation};

class ApplicationOrgTypeListSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // slug => [ru, kk, en]
            
            'college' => ['Колледж', 'Колледж', 'College'],
            'technical_school' => ['Техникум', 'Техникум', 'Technical school'],
            'university' => ['Университет', 'Университет', 'University'],
            'institute' => ['Институт', 'Институт', 'Institute'],
            'academy' => ['Академия', 'Академия', 'Academy'],
            'training_center' => ['Учебный центр', 'Оқу орталығы', 'Training center'],
            'language_center' => ['Языковой центр', 'Тілдік орталық', 'Language center'],
            'research_institute' => ['Научно-исследовательский институт', 'Ғылыми-зерттеу институты', 'Research institute'],
            'art_school' => ['Школа искусств', 'Өнер мектебі', 'Art school'],
            'music_school' => ['Музыкальная школа', 'Музыка мектебі', 'Music school'],
            'sports_school' => ['Спортивная школа', 'Спорт мектебі', 'Sports school'],
            'private_center' => ['Частный образовательный центр', 'Жеке білім беру орталығы', 'Private education center'],
            'other' => ['Другое', 'Басқа', 'Other'],
        ];

        $created = 0;
        foreach ($types as $slug => [$ru, $kk, $en]) {
            $type = ApplicationOrgType::firstOrCreate(['slug' => $slug]);
            ApplicationOrgTypeTranslation::updateOrCreate(
                ['org_type_id' => $type->id, 'locale' => 'ru'],
                ['name' => $ru]
            );
            ApplicationOrgTypeTranslation::updateOrCreate(
                ['org_type_id' => $type->id, 'locale' => 'kk'],
                ['name' => $kk]
            );
            ApplicationOrgTypeTranslation::updateOrCreate(
                ['org_type_id' => $type->id, 'locale' => 'en'],
                ['name' => $en]
            );
            if ($type->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command?->info("Institution categories upserted. New created: {$created}");
    }
}


