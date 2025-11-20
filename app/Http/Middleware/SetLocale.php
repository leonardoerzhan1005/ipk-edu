<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? 'ru';

        if (!in_array($locale, ['ru', 'kk', 'en'])) {
            $locale = 'ru';
        }

        App::setLocale($locale);

        // Устанавливаем локаль Carbon
        Carbon::setLocale($locale);

        // Если это казахский, вручную подключаем перевод
        if ($locale === 'kk') {
            $kkLangPath = resource_path('lang/vendor/carbon/kk.php');
            if (file_exists($kkLangPath)) {
                Carbon::setLocale('kk');
            } else {
                // fallback — если нет файла, пусть будет ru
                Carbon::setLocale('ru');
            }
        }

        return $next($request);
    }
}
