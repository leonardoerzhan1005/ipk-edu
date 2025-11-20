<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application\Application;

class TestApplicationController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:application-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test application controller functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing application controller...');
        
        // Проверяем, есть ли заявки в базе
        $applications = Application::all();
        $this->info("Found {$applications->count()} applications");
        
        if ($applications->isEmpty()) {
            $this->error('No applications found in database');
            return 1;
        }
        
        // Показываем первую заявку
        $firstApplication = $applications->first();
        $this->info("First application ID: {$firstApplication->id}");
        $this->info("First application email: {$firstApplication->email}");
        $this->info("First application status: {$firstApplication->status}");
        
        // Проверяем связи
        if ($firstApplication->faculty) {
            $this->info("Faculty: {$firstApplication->faculty->name}");
        } else {
            $this->warn("No faculty associated");
        }
        
        if ($firstApplication->specialty) {
            $this->info("Specialty: {$firstApplication->specialty->name}");
        } else {
            $this->warn("No specialty associated");
        }
        
        if ($firstApplication->course) {
            $this->info("Course: {$firstApplication->course->title}");
        } else {
            $this->warn("No course associated");
        }
        
        // Проверяем атрибуты
        $this->info("Full name (RU): {$firstApplication->full_name_ru}");
        $this->info("Full name (KK): {$firstApplication->full_name_kk}");
        $this->info("Full name (EN): {$firstApplication->full_name_en}");
        $this->info("Full name (current): {$firstApplication->full_name}");
        
        $this->info('Application controller test completed successfully!');
        return 0;
    }
}
