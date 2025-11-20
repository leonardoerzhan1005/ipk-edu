<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Работаем только с админскими роутами
        if (!$request->is('admin*')) {
            return $next($request);
        }
        
        // Получаем язык из URL
        $locale = $request->segment(2); // admin/{locale}/...
        
        // Временно логируем для отладки
        \Log::info('AdminLocaleMiddleware: URL = ' . $request->getRequestUri() . ', Locale = ' . $locale . ', Method = ' . $request->method());
        
        // Проверяем, что язык валидный
        if (in_array($locale, ['en', 'ru', 'kk'])) {
            app()->setLocale($locale);
            // Ensure URL generator automatically includes current locale for admin routes
            \URL::defaults(['locale' => $locale]);
            \Log::info('AdminLocaleMiddleware: Set locale to ' . $locale . ', continuing...');
            return $next($request);
        }
        
        // Если язык не указан или невалидный, редиректим на дефолтный
        $defaultLocale = config('app.locale', 'en');
        $path = str_replace('/admin', '', $request->getRequestUri());
        $redirectUrl = "/admin/{$defaultLocale}{$path}";
        \Log::info('AdminLocaleMiddleware: Redirecting to ' . $redirectUrl);
        return redirect()->to($redirectUrl);
    }
}
