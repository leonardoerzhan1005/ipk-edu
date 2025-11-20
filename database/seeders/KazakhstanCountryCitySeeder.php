<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Application\{ApplicationCountry, ApplicationCountryTranslation, ApplicationCity, ApplicationCityTranslation};

class KazakhstanCountryCitySeeder extends Seeder
{
    public function run(): void
    {
        // Upsert country KZ
        $country = ApplicationCountry::firstOrCreate(['code' => 'KZ']);
        ApplicationCountryTranslation::updateOrCreate(
            ['country_id' => $country->id, 'locale' => 'ru'],
            ['name' => 'Казахстан']
        );
        ApplicationCountryTranslation::updateOrCreate(
            ['country_id' => $country->id, 'locale' => 'kk'],
            ['name' => 'Қазақстан']
        );
        ApplicationCountryTranslation::updateOrCreate(
            ['country_id' => $country->id, 'locale' => 'en'],
            ['name' => 'Kazakhstan']
        );

        $cities = $this->loadCitiesFromJson();
        if (empty($cities)) {
            $cities = $this->defaultCities();
        }

        $created = 0;
        foreach ($cities as $city) {
            $slug = $city['slug'] ?? null;
            $ru = $city['ru'] ?? null;
            if (!$slug) {
                if ($ru) {
                    $slug = Str::slug($ru) ?: Str::slug(Str::ascii($ru));
                }
            }
            if (!$slug || !$ru) {
                continue;
            }
            $kk = $city['kk'] ?? $ru;
            $en = $city['en'] ?? $ru;

            $model = ApplicationCity::firstOrCreate([
                'country_id' => $country->id,
                'slug' => $slug,
            ]);

            ApplicationCityTranslation::updateOrCreate(
                ['city_id' => $model->id, 'locale' => 'ru'],
                ['name' => $ru]
            );
            ApplicationCityTranslation::updateOrCreate(
                ['city_id' => $model->id, 'locale' => 'kk'],
                ['name' => $kk]
            );
            ApplicationCityTranslation::updateOrCreate(
                ['city_id' => $model->id, 'locale' => 'en'],
                ['name' => $en]
            );

            if ($model->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command?->info("Kazakhstan cities upserted. New created: {$created}");
    }

    private function loadCitiesFromJson(): array
    {
        $path = storage_path('app/import/kz_cities.json');
        if (!file_exists($path)) {
            return [];
        }
        $json = json_decode((string) file_get_contents($path), true);
        return is_array($json) ? $json : [];
    }

    private function defaultCities(): array
    {
        // Starter list: oblast centers and major cities. Extend via storage/app/import/kz_cities.json
        return [
            ['slug' => 'almaty', 'ru' => 'Алматы', 'kk' => 'Алматы', 'en' => 'Almaty'],
            ['slug' => 'astana', 'ru' => 'Астана', 'kk' => 'Астана', 'en' => 'Astana'],
            ['slug' => 'shymkent', 'ru' => 'Шымкент', 'kk' => 'Шымкент', 'en' => 'Shymkent'],
            ['slug' => 'aktobe', 'ru' => 'Актобе', 'kk' => 'Ақтөбе', 'en' => 'Aktobe'],
            ['slug' => 'atyrau', 'ru' => 'Атырау', 'kk' => 'Атырау', 'en' => 'Atyrau'],
            ['slug' => 'karaganda', 'ru' => 'Караганда', 'kk' => 'Қарағанды', 'en' => 'Karaganda'],
            ['slug' => 'pavlodar', 'ru' => 'Павлодар', 'kk' => 'Павлодар', 'en' => 'Pavlodar'],
            ['slug' => 'oskemen', 'ru' => 'Усть-Каменогорск', 'kk' => 'Өскемен', 'en' => 'Oskemen'],
            ['slug' => 'semey', 'ru' => 'Семей', 'kk' => 'Семей', 'en' => 'Semey'],
            ['slug' => 'kostanay', 'ru' => 'Костанай', 'kk' => 'Қостанай', 'en' => 'Kostanay'],
            ['slug' => 'kyzylorda', 'ru' => 'Кызылорда', 'kk' => 'Қызылорда', 'en' => 'Kyzylorda'],
            ['slug' => 'petropavl', 'ru' => 'Петропавл', 'kk' => 'Петропавл', 'en' => 'Petropavl'],
            ['slug' => 'oral', 'ru' => 'Уральск', 'kk' => 'Орал', 'en' => 'Oral'],
            ['slug' => 'kokshetau', 'ru' => 'Кокшетау', 'kk' => 'Көкшетау', 'en' => 'Kokshetau'],
            ['slug' => 'taldykorgan', 'ru' => 'Талдыкорган', 'kk' => 'Талдықорған', 'en' => 'Taldykorgan'],
            ['slug' => 'turkistan', 'ru' => 'Туркестан', 'kk' => 'Түркістан', 'en' => 'Turkistan'],
            ['slug' => 'aktau', 'ru' => 'Актау', 'kk' => 'Ақтау', 'en' => 'Aktau'],
            ['slug' => 'taraz', 'ru' => 'Тараз', 'kk' => 'Тараз', 'en' => 'Taraz'],
            ['slug' => 'ekibastuz', 'ru' => 'Экибастуз', 'kk' => 'Екібастұз', 'en' => 'Ekibastuz'],
            ['slug' => 'rudny', 'ru' => 'Рудный', 'kk' => 'Рудный', 'en' => 'Rudny'],
            ['slug' => 'temirtau', 'ru' => 'Темиртау', 'kk' => 'Теміртау', 'en' => 'Temirtau'],
            ['slug' => 'zhezkazgan', 'ru' => 'Жезказган', 'kk' => 'Жезқазған', 'en' => 'Zhezkazgan'],
            ['slug' => 'zhanaozen', 'ru' => 'Жанаозен', 'kk' => 'Жаңаөзен', 'en' => 'Zhanaozen'],
            ['slug' => 'kulsary', 'ru' => 'Кульсары', 'kk' => 'Құлсары', 'en' => 'Kulsary'],
            ['slug' => 'kandyagash', 'ru' => 'Кандыагаш', 'kk' => 'Қандыағаш', 'en' => 'Kandyagash'],
            ['slug' => 'khromtau', 'ru' => 'Хромтау', 'kk' => 'Хромтау', 'en' => 'Khromtau'],
            ['slug' => 'shalkar', 'ru' => 'Шалкар', 'kk' => 'Шалқар', 'en' => 'Shalkar'],
            ['slug' => 'aralsk', 'ru' => 'Аральск', 'kk' => 'Арал', 'en' => 'Aral'],
            ['slug' => 'baikonur', 'ru' => 'Байконур', 'kk' => 'Байқоңыр', 'en' => 'Baikonur'],
            ['slug' => 'balkhash', 'ru' => 'Балхаш', 'kk' => 'Балқаш', 'en' => 'Balkhash'],
            ['slug' => 'zhetikara', 'ru' => 'Житикара', 'kk' => 'Жітіқара', 'en' => 'Zhetikara'],
            ['slug' => 'lisakovsk', 'ru' => 'Лисаковск', 'kk' => 'Лисаков', 'en' => 'Lisakovsk'],
            ['slug' => 'arkalyk', 'ru' => 'Аркалык', 'kk' => 'Арқалық', 'en' => 'Arkalyk'],
            ['slug' => 'karazhal', 'ru' => 'Каражал', 'kk' => 'Қаражал', 'en' => 'Karazhal'],
            ['slug' => 'saran', 'ru' => 'Сарань', 'kk' => 'Саран', 'en' => 'Saran'],
            ['slug' => 'abai', 'ru' => 'Абай', 'kk' => 'Абай', 'en' => 'Abai'],
            ['slug' => 'shchuchinsk', 'ru' => 'Щучинск', 'kk' => 'Щучинск', 'en' => 'Shchuchinsk'],
            ['slug' => 'stepnogorsk', 'ru' => 'Степногорск', 'kk' => 'Степногорск', 'en' => 'Stepnogorsk'],
            ['slug' => 'ridder', 'ru' => 'Риддер', 'kk' => 'Риддер', 'en' => 'Ridder'],
            ['slug' => 'stepnyak', 'ru' => 'Степняк', 'kk' => 'Степняк', 'en' => 'Stepnyak'],
            ['slug' => 'atbasar', 'ru' => 'Атбасар', 'kk' => 'Атбасар', 'en' => 'Atbasar'],
            ['slug' => 'derzhavinsk', 'ru' => 'Державинск', 'kk' => 'Державинск', 'en' => 'Derzhavinsk'],
            ['slug' => 'akkol', 'ru' => 'Акколь', 'kk' => 'Ақкөл', 'en' => 'Akkol'],
            ['slug' => 'esik', 'ru' => 'Есик', 'kk' => 'Есік', 'en' => 'Yesik'],
            ['slug' => 'talghar', 'ru' => 'Талгар', 'kk' => 'Талғар', 'en' => 'Talgar'],
            ['slug' => 'zharkent', 'ru' => 'Жаркент', 'kk' => 'Жаркент', 'en' => 'Zharkent'],
            ['slug' => 'tekeli', 'ru' => 'Текели', 'kk' => 'Текелі', 'en' => 'Tekeli'],
            ['slug' => 'konaev', 'ru' => 'Конаев', 'kk' => 'Қонаев', 'en' => 'Konaev'],
            ['slug' => 'usharal', 'ru' => 'Ушарал', 'kk' => 'Ұшарал', 'en' => 'Usharal'],
            ['slug' => 'ushtobe', 'ru' => 'Уштобе', 'kk' => 'Үштөбе', 'en' => 'Ushtobe'],
            ['slug' => 'ayagoz', 'ru' => 'Аягоз', 'kk' => 'Аягөз', 'en' => 'Ayagoz'],
            ['slug' => 'zaisan', 'ru' => 'Зайсан', 'kk' => 'Зайсан', 'en' => 'Zaisan'],
            ['slug' => 'kurchatov', 'ru' => 'Курчатов', 'kk' => 'Курчатов', 'en' => 'Kurchatov'],
            ['slug' => 'karatau', 'ru' => 'Каратау', 'kk' => 'Қаратау', 'en' => 'Karatau'],
            ['slug' => 'kentau', 'ru' => 'Кентау', 'kk' => 'Кентау', 'en' => 'Kentau'],
            ['slug' => 'arys', 'ru' => 'Арыс', 'kk' => 'Арыс', 'en' => 'Arys'],
            ['slug' => 'shardara', 'ru' => 'Шардара', 'kk' => 'Шардара', 'en' => 'Shardara'],
            ['slug' => 'zhetysai', 'ru' => 'Жетысай', 'kk' => 'Жетісай', 'en' => 'Zhetysai'],
            ['slug' => 'saryagash', 'ru' => 'Сарыагаш', 'kk' => 'Сарыағаш', 'en' => 'Saryagash'],
            ['slug' => 'aksay', 'ru' => 'Аксай', 'kk' => 'Ақсай', 'en' => 'Aksay'],
            ['slug' => 'fort-shevchenko', 'ru' => 'Форт-Шевченко', 'kk' => 'Форт-Шевченко', 'en' => 'Fort-Shevchenko'],
            ['slug' => 'beyneu', 'ru' => 'Бейнеу', 'kk' => 'Бейнеу', 'en' => 'Beyneu'],
            ['slug' => 'shetpe', 'ru' => 'Шетпе', 'kk' => 'Шетпе', 'en' => 'Shetpe'],
        ];
    }
}


