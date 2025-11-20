<?php



/** convert minutes to hours */

use App\Models\Cart;

if(!function_exists('convertMinutesToHours')) {
    function convertMinutesToHours(int $minutes) : string {
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        return sprintf('%dh %02dm', $hours, $minutes); // Returns format : 1h 30m
    }

}

if(!function_exists('user')) {
    function user() {
        return auth('web')->user();
    }
}


if(!function_exists('adminUser')) {
    function adminUser() {
        return auth('admin')->user();
    }
}

/** calculate cart total */
if(!function_exists('cartCount')) {
    function cartCount() {
        return Cart::where('user_id', user()?->id)->count();
    }
}


/** calculate cart total */
if(!function_exists('cartTotal')) {
    function cartTotal() {
        $total = 0;

        $cart = Cart::where('user_id', user()->id)->get();

        foreach($cart as $item) {
            if($item->course->discount > 0) {
                $total += $item->course->discount;
            }else {
                $total += $item->course->price;
            }
        }

        return $total;
    }
}

/** calculate cart total */
if(!function_exists('calculateCommission')) {
    function calculateCommission($amount, $commission) {
        return $amount == 0 ? 0 : ($amount * $commission) / 100;
    }
}

/** Sidebar Item Active */
if(!function_exists('sidebarItemActive')) {
    function sidebarItemActive(array $routes) {

        foreach($routes as $route) {
            if(request()->routeIs($route)) {
                return 'active';
            }
        }
    }
}

/** Get Current Locale */
if(!function_exists('currentLocale')) {
    function currentLocale() {
        return app()->getLocale();
    }
}

/** Get Supported Locales */
if(!function_exists('supportedLocales')) {
    function supportedLocales() {
        return config('locales.supported', ['en', 'ru', 'kk']);
    }
}

/** Get Locale Name */
if(!function_exists('localeName')) {
    function localeName($locale = null) {
        $locale = $locale ?: currentLocale();
        $names = config('locales.names', [
            'en' => 'English',
            'ru' => 'Русский',
            'kk' => 'Қазақша'
        ]);
        return $names[$locale] ?? strtoupper($locale);
    }
}

/** Get Locale Flag */
if(!function_exists('localeFlag')) {
    function localeFlag($locale = null) {
        $locale = $locale ?: currentLocale();
        $flags = config('locales.flags', [
            'en' => '🇺🇸',
            'ru' => '🇷🇺',
            'kk' => '🇰🇿'
        ]);
        return $flags[$locale] ?? '🌐';
    }
}

/** Check if locale is current */
if(!function_exists('isCurrentLocale')) {
    function isCurrentLocale($locale) {
        return currentLocale() === $locale;
    }
}

/** Generate localized route URL */
if(!function_exists('localizedRoute')) {
    function localizedRoute($name, $parameters = [], $absolute = true) {
        // Если $parameters - строка, преобразуем в массив
        if (is_string($parameters)) {
            $parameters = ['slug' => $parameters];
        }
        
        // Убеждаемся, что $parameters - массив
        if (!is_array($parameters)) {
            $parameters = [];
        }
        
        // Фильтруем null и пустые значения, но оставляем 0 и false
        $parameters = array_filter($parameters, function($value) {
            return $value !== null && $value !== '';
        });
        
        $parameters['locale'] = $parameters['locale'] ?? app()->getLocale();
        return route($name, $parameters, $absolute);
    }
}

/** Generate localized URL */
if(!function_exists('localizedUrl')) {
    function localizedUrl($path = '', $parameters = [], $secure = null) {
        // Убеждаемся, что $parameters - массив
        if (!is_array($parameters)) {
            $parameters = [];
        }
        
        $locale = app()->getLocale();
        $localizedPath = $locale . '/' . ltrim($path, '/');
        return url($localizedPath, $parameters, $secure);
    }
}

/** Generate localized URL with translated paths */
if(!function_exists('localizedUrlWithPath')) {
    function localizedUrlWithPath($path = '', $parameters = [], $secure = null) {
        // Убеждаемся, что $parameters - массив
        if (!is_array($parameters)) {
            $parameters = [];
        }
        
        $locale = app()->getLocale();
        
        // Определяем переводы путей для каждого языка
        $localizedPaths = [
            'en' => [
                'courses' => 'courses',
                'blog' => 'blog',
                'about' => 'about',
                'contact' => 'contact',
                'cart' => 'cart',
                'checkout' => 'checkout',
                'dashboard' => 'dashboard',
                'profile' => 'profile',
                'login' => 'login',
                'register' => 'register',
                'password' => 'password',
                'verification' => 'verification',
                'logout' => 'logout',
            ],
            'ru' => [
                'courses' => 'курсы',
                'blog' => 'блог',
                'about' => 'о-нас',
                'contact' => 'контакты',
                'cart' => 'корзина',
                'checkout' => 'оформление',
                'dashboard' => 'панель',
                'profile' => 'профиль',
                'login' => 'вход',
                'register' => 'регистрация',
                'password' => 'пароль',
                'verification' => 'подтверждение',
                'logout' => 'выход',
            ],
            'kk' => [
                'courses' => 'курстар',
                'blog' => 'блог',
                'about' => 'біз-туралы',
                'contact' => 'байланыс',
                'cart' => 'себет',
                'checkout' => 'тапсырыс',
                'dashboard' => 'панель',
                'profile' => 'профиль',
                'login' => 'кіру',
                'register' => 'тіркеу',
                'password' => 'құпия-сөз',
                'verification' => 'растау',
                'logout' => 'шығу',
            ],
        ];
        
        $paths = $localizedPaths[$locale] ?? $localizedPaths['en'];
        
        // Заменяем английские пути на локализованные
        foreach ($paths as $enPath => $localizedPath) {
            $path = str_replace($enPath, $localizedPath, $path);
        }
        
        $localizedPath = $locale . '/' . ltrim($path, '/');
        return url($localizedPath, $parameters, $secure);
    }
}

/** Generate localized route with translated paths */
if(!function_exists('localizedRouteWithPath')) {
    function localizedRouteWithPath($name, $parameters = [], $absolute = true) {
        // Если $parameters - строка, преобразуем в массив
        if (is_string($parameters)) {
            $parameters = ['slug' => $parameters];
        }
        
        // Убеждаемся, что $parameters - массив
        if (!is_array($parameters)) {
            $parameters = [];
        }
        
        $locale = app()->getLocale();
        $parameters['locale'] = $parameters['locale'] ?? $locale;
        
        // Получаем имя маршрута с локалью
        $localizedName = $locale . '.' . $name;
        
        return route($localizedName, $parameters, $absolute);
    }
}

/** Get current localized path without locale prefix */
if(!function_exists('currentLocalizedPath')) {
    function currentLocalizedPath() {
        $path = request()->path();
        $locale = app()->getLocale();
        
        // Убираем префикс локали
        if (str_starts_with($path, $locale . '/')) {
            $path = substr($path, strlen($locale) + 1);
        }
        
        return $path;
    }
}

/** Check if current path matches localized path */
if(!function_exists('isLocalizedPath')) {
    function isLocalizedPath($path) {
        $currentPath = currentLocalizedPath();
        return $currentPath === $path;
    }
}

