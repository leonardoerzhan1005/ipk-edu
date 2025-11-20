<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class TestAdminAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:admin-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing admin authentication...');
        
        // Проверяем, есть ли админ в базе
        $admin = Admin::first();
        if (!$admin) {
            $this->error('No admin found in database');
            return 1;
        }
        
        $this->info("Admin found: {$admin->email}");
        
        // Пытаемся аутентифицировать админа
        if (Auth::guard('admin')->attempt(['email' => $admin->email, 'password' => '12345678'])) {
            $this->info('Admin authentication successful!');
            
            // Проверяем, аутентифицирован ли админ
            if (Auth::guard('admin')->check()) {
                $this->info('Admin is authenticated');
                
                // Получаем текущего аутентифицированного админа
                $currentAdmin = Auth::guard('admin')->user();
                $this->info("Current admin: {$currentAdmin->email}");
                
                // Выходим из системы
                Auth::guard('admin')->logout();
                $this->info('Admin logged out');
            } else {
                $this->error('Admin is not authenticated after login attempt');
            }
        } else {
            $this->error('Admin authentication failed');
        }
        
        return 0;
    }
}
