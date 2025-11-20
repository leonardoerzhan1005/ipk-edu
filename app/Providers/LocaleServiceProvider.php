<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Регистрируем языковые настройки в конфигурации
        $this->registerLocaleConfig();
        
        // Устанавливаем язык из сессии при загрузке приложения
        $this->setLocaleFromSession();
    }

    /**
     * Регистрируем конфигурацию локализации
     */
    protected function registerLocaleConfig(): void
    {
        // Убеждаемся, что конфигурация locales загружена
        if (!config('locales')) {
            config([
                'locales.supported' => ['en', 'ru', 'kk'],
                'locales.default' => 'ru',
                'locales.fallback' => 'en',
                'locales.names' => [
                    'en' => 'English',
                    'ru' => 'Русский',
                    'kk' => 'Қазақша'
                ],
                'locales.flags' => [
                    'en' => '🇺🇸',
                    'ru' => '🇷🇺',
                    'kk' => '🇰🇿'
                ]
            ]);
        }
    }

    /**
     * Устанавливаем язык из сессии
     */
    protected function setLocaleFromSession(): void
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            $supported = config('locales.supported', ['en', 'ru', 'kk']);
            
            if (in_array($locale, $supported)) {
                App::setLocale($locale);
                Carbon::setLocale($locale);
            }
        }
    }
}
