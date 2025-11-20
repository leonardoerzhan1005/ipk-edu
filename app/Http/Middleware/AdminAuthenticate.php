<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('AdminAuthenticate middleware called');
        \Log::info('Request URL: ' . $request->url());
        \Log::info('Admin guard check: ' . (Auth::guard('admin')->check() ? 'true' : 'false'));
        
        if (!Auth::guard('admin')->check()) {
            \Log::info('Admin not authenticated, redirecting to login');
            // Получаем locale из URL
            $locale = $request->route('locale') ?? app()->getLocale();
            
            // Перенаправляем на страницу входа для админа с правильным locale
            return redirect()->route('admin.login', ['locale' => $locale]);
        }

        \Log::info('Admin authenticated, continuing...');
        return $next($request);
    }
}
