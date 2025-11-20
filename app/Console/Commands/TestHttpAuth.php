<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Cookie\CookieJar;

class TestHttpAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:http-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test HTTP authentication for admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing HTTP authentication...');
        
        $baseUrl = 'http://127.0.0.1:8000';
        
        // Создаем cookie jar
        $cookieJar = new CookieJar();
        
        // Создаем сессию
        $session = Http::withOptions([
            'cookies' => $cookieJar,
            'timeout' => 30
        ]);
        
        // Получаем CSRF токен со страницы входа
        $this->info('Getting CSRF token...');
        $loginPage = $session->get("{$baseUrl}/admin/ru/login");
        
        if ($loginPage->status() !== 200) {
            $this->error("Failed to get login page. Status: {$loginPage->status()}");
            return 1;
        }
        
        // Извлекаем CSRF токен из HTML
        preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $loginPage->body(), $matches);
        $csrfToken = $matches[1] ?? null;
        
        if (!$csrfToken) {
            $this->error('CSRF token not found');
            return 1;
        }
        
        $this->info("CSRF token: {$csrfToken}");
        
        // Пытаемся войти
        $this->info('Attempting login...');
        $loginResponse = $session->post("{$baseUrl}/admin/ru/login", [
            '_token' => $csrfToken,
            'email' => 'admin@gmail.com',
            'password' => '12345678'
        ]);
        
        $this->info("Login response status: {$loginResponse->status()}");
        
        if ($loginResponse->status() === 200 && strpos($loginResponse->body(), 'Dashboard') !== false) {
            $this->info('Login successful (dashboard page)');
            
            // Проверяем, можем ли мы получить доступ к защищенной странице
            $this->info('Testing protected route...');
            $protectedResponse = $session->get("{$baseUrl}/admin/ru/admin-training-applications/view/6");
            
            $this->info("Protected route status: {$protectedResponse->status()}");
            
            if ($protectedResponse->status() === 200) {
                $this->info('Protected route accessible!');
                
                // Проверяем содержимое
                if (strpos($protectedResponse->body(), 'Application') !== false) {
                    $this->info('Page contains application data');
                } else {
                    $this->warn('Page does not contain expected content');
                }
            } else {
                $this->error('Protected route not accessible');
                $this->info('Response body: ' . substr($protectedResponse->body(), 0, 500));
            }
        } else {
            $this->error('Login failed');
            $this->info('Response body: ' . substr($loginResponse->body(), 0, 500));
        }
        
        return 0;
    }
}
