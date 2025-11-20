<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Application\{
    ApplicationFaculty,
    ApplicationFacultyTranslation,
    ApplicationSpecialty,
    ApplicationSpecialtyTranslation
};

class ApplicationFacultySpecialtySeeder extends Seeder
{
    /** @var array<string, array{kk?: string, en?: string}> */
    private array $facultyTranslations = [];

    /** @var array<string, array<string, array{kk?: string, en?: string}>> */
    private array $specialtyTranslationsByFaculty = [];

    /** @var array<string, array{kk?: string, en?: string}> */
    private array $specialtyTranslationsFlat = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->loadTranslations();
        // Prefer the structured RU spec if available
        $ruList = $this->parseMd(base_path('Faculty_Spec_ru.md'), 'ru');

        $createdFaculties = 0;
        $createdSpecialties = 0;

        if (!empty($ruList)) {
            foreach ($ruList as $fac) {
                $facultyNameRu = $fac['name'] ?? null;
                if (!$facultyNameRu) {
                    continue;
                }
                [$facultyModel, $facultySlug, $justCreated] = $this->upsertFaculty($facultyNameRu);
                if ($justCreated) {
                    $createdFaculties++;
                }
                $specs = $fac['specialties'] ?? [];
                foreach ($specs as $specRu) {
                    $just = $this->upsertSpecialty($facultyModel->id, $facultySlug, $specRu);
                    if ($just) {
                        $createdSpecialties++;
                    }
                }
            }
            $this->command?->info("Faculties created: {$createdFaculties}; Specialties created: {$createdSpecialties}");
            return;
        }

        // Fallback to raw Faculty_Spec.md parsing if RU MD not present
        $path = base_path('Faculty_Spec.md');
        if (!file_exists($path)) {
            $this->command?->warn('Faculty_Spec_ru.md and Faculty_Spec.md not found; skipping.');
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            $this->command?->error('Unable to read Faculty_Spec.md');
            return;
        }

        $currentFaculty = null;
        $facultySlug = null;
        foreach ($lines as $rawLine) {
            $line = trim($rawLine);
            if ($line === '') {
                continue;
            }
            $facultyNameRu = null;
            if (preg_match('/^Faculty\s*--\s*(.+)$/u', $line, $m)) {
                $facultyNameRu = trim($m[1]);
            } elseif (!preg_match('/^\d+\s*\./u', $line)) {
                if (mb_strlen($line) >= 2) {
                    $facultyNameRu = $line;
                }
            }
            if ($facultyNameRu) {
                [$currentFaculty, $facultySlug, $justCreated] = $this->upsertFaculty($facultyNameRu);
                if ($justCreated) {
                    $createdFaculties++;
                }
                continue;
            }
            if (preg_match('/^\d+\s*\.[\t\s]*(.+)$/u', $line, $m)) {
                if (!$currentFaculty) {
                    continue;
                }
                $name = trim($m[1]);
                if ($name === '') {
                    continue;
                }
                $justCreated = $this->upsertSpecialty($currentFaculty->id, $facultySlug, $name);
                if ($justCreated) {
                    $createdSpecialties++;
                }
            }
        }
        $this->command?->info("Faculties created: {$createdFaculties}; Specialties created: {$createdSpecialties}");
    }

    /**
     * @return array{0: ApplicationFaculty, 1: string, 2: bool}
     */
    private function upsertFaculty(string $nameRu): array
    {
        $slug = Str::slug($nameRu) ?: Str::slug(Str::ascii($nameRu)) ?: 'faculty';
        $faculty = ApplicationFaculty::firstOrCreate(['slug' => $slug]);
        $this->upsertFacultyTranslations($faculty, $slug, $nameRu);
        return [$faculty, $slug, $faculty->wasRecentlyCreated];
    }

    private function upsertFacultyTranslations(ApplicationFaculty $faculty, string $facultySlug, string $nameRu): void
    {
        $kk = $this->facultyTranslations[$facultySlug]['kk'] ?? $nameRu;
        $en = $this->facultyTranslations[$facultySlug]['en'] ?? $nameRu;

        ApplicationFacultyTranslation::updateOrCreate(
            ['faculty_id' => $faculty->id, 'locale' => 'ru'],
            ['name' => $nameRu]
        );
        ApplicationFacultyTranslation::updateOrCreate(
            ['faculty_id' => $faculty->id, 'locale' => 'kk'],
            ['name' => $kk]
        );
        ApplicationFacultyTranslation::updateOrCreate(
            ['faculty_id' => $faculty->id, 'locale' => 'en'],
            ['name' => $en]
        );
    }

    private function upsertSpecialty(int $facultyId, string $facultySlug, string $nameRu): bool
    {
        $specSlugSimple = (Str::slug($nameRu) ?: Str::slug(Str::ascii($nameRu)) ?: 'specialty');
        $slug = $facultySlug.'-'.$specSlugSimple;
        $specialty = ApplicationSpecialty::firstOrCreate(
            ['slug' => $slug],
            ['faculty_id' => $facultyId]
        );
        if ($specialty->faculty_id !== $facultyId) {
            $specialty->faculty_id = $facultyId;
            $specialty->save();
        }
        $this->upsertSpecialtyTranslations($specialty, $nameRu, $facultySlug, $specSlugSimple, $slug);
        return $specialty->wasRecentlyCreated;
    }

    private function upsertSpecialtyTranslations(ApplicationSpecialty $specialty, string $nameRu, string $facultySlug, string $specSlugSimple, string $combinedSlug): void
    {
        $kk = $this->specialtyTranslationsByFaculty[$facultySlug][$specSlugSimple]['kk']
            ?? $this->specialtyTranslationsFlat[$combinedSlug]['kk']
            ?? $nameRu;
        $en = $this->specialtyTranslationsByFaculty[$facultySlug][$specSlugSimple]['en']
            ?? $this->specialtyTranslationsFlat[$combinedSlug]['en']
            ?? $nameRu;

        ApplicationSpecialtyTranslation::updateOrCreate(
            ['specialty_id' => $specialty->id, 'locale' => 'ru'],
            ['name' => $nameRu]
        );
        ApplicationSpecialtyTranslation::updateOrCreate(
            ['specialty_id' => $specialty->id, 'locale' => 'kk'],
            ['name' => $kk]
        );
        ApplicationSpecialtyTranslation::updateOrCreate(
            ['specialty_id' => $specialty->id, 'locale' => 'en'],
            ['name' => $en]
        );
    }

    private function loadTranslations(): void
    {
        $ru = $this->parseMd(base_path('Faculty_Spec_ru.md'), 'ru');
        $kk = $this->parseMd(base_path('Faculty_Spec_kk.md'), 'kk');
        $en = $this->parseMd(base_path('Faculty_Spec_en.md'), 'en');

        if (empty($ru)) {
            $this->command?->warn('Faculty_Spec_ru.md not found or empty; kk/en will fallback to ru names from Faculty_Spec.md');
            return;
        }

        // Align faculties and specialties by index
        foreach ($ru as $i => $ruFac) {
            $ruFacultyName = $ruFac['name'] ?? '';
            $facultySlug = \Illuminate\Support\Str::slug($ruFacultyName) ?: \Illuminate\Support\Str::slug(\Illuminate\Support\Str::ascii($ruFacultyName));
            if (!$facultySlug) {
                continue;
            }
            $kkName = $kk[$i]['name'] ?? $ruFacultyName;
            $enName = $en[$i]['name'] ?? $ruFacultyName;
            $this->facultyTranslations[$facultySlug] = [
                'kk' => $kkName,
                'en' => $enName,
            ];

            $ruSpecs = $ruFac['specialties'] ?? [];
            $kkSpecs = $kk[$i]['specialties'] ?? [];
            $enSpecs = $en[$i]['specialties'] ?? [];
            foreach ($ruSpecs as $j => $ruSpecName) {
                $specSlugSimple = \Illuminate\Support\Str::slug($ruSpecName) ?: \Illuminate\Support\Str::slug(\Illuminate\Support\Str::ascii($ruSpecName));
                if (!$specSlugSimple) {
                    continue;
                }
                $kkSpecName = $kkSpecs[$j] ?? $ruSpecName;
                $enSpecName = $enSpecs[$j] ?? $ruSpecName;
                $this->specialtyTranslationsByFaculty[$facultySlug][$specSlugSimple] = [
                    'kk' => $kkSpecName,
                    'en' => $enSpecName,
                ];
            }
        }
    }

    /**
     * Parse a Markdown spec into ordered faculties with specialties.
     * @return array<int, array{name: string, specialties: array<int, string>}>
     */
    private function parseMd(string $path, string $lang): array
    {
        $result = [];
        if (!file_exists($path)) {
            return $result;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return $result;
        }

        $current = null;
        foreach ($lines as $raw) {
            $line = trim($raw);
            if ($line === '') {
                continue;
            }

            // Headers like: "## Biology Faculty" (en), "## Биология факультеті" (kk)
            if (preg_match('/^##\s*(.+)$/u', $line, $m)) {
                $header = trim($m[1]);
                $name = $header;
                if ($lang === 'en') {
                    $name = preg_replace('/\s*Faculty$/u', '', $header) ?? $header;
                } elseif ($lang === 'kk') {
                    $name = preg_replace('/\s*факультеті$/u', '', $header) ?? $header;
                }
                if ($current) {
                    $result[] = $current;
                }
                $current = ['name' => trim($name), 'specialties' => []];
                continue;
            }

            // RU variant sometimes uses: "Faculty -- <name>"
            if ($lang === 'ru' && preg_match('/^Faculty\s*--\s*(.+)$/u', $line, $m)) {
                if ($current) {
                    $result[] = $current;
                }
                $current = ['name' => trim($m[1]), 'specialties' => []];
                continue;
            }

            // RU fallback: treat non-numbered, non-header lines as faculty names
            if ($lang === 'ru' && !preg_match('/^\d+\s*\./u', $line) && !str_starts_with($line, '#') && !str_starts_with($line, 'Faculty --')) {
                if ($current) {
                    $result[] = $current;
                }
                $current = ['name' => $line, 'specialties' => []];
                continue;
            }

            // Specialty items: "N. Name"
            if (preg_match('/^\d+\s*\.[\t\s]*(.+)$/u', $line, $m)) {
                if ($current) {
                    $current['specialties'][] = trim($m[1]);
                }
                continue;
            }
        }

        if ($current) {
            $result[] = $current;
        }
        return $result;
    }
}


