<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\IssuedCertificate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssuedCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->take(5)->get();
        $courses = Course::query()->take(3)->get();
        if ($users->isEmpty() || $courses->isEmpty()) {
            return; // nothing to seed
        }

        $year = date('Y');
        $seq = 1;
        foreach ($users as $user) {
            foreach ($courses as $course) {
                $code = sprintf('CERT-%s-%06d', $year, $seq++);
                IssuedCertificate::updateOrCreate(
                    ['user_id' => $user->id, 'course_id' => $course->id],
                    [
                        'code' => $code,
                        'file_path' => null,
                        'issued_at' => now()->subDays(rand(0, 90)),
                    ]
                );
            }
        }
    }
}


