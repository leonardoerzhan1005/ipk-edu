@php
    $currentLocale = app()->getLocale();
    $supportedLocales = config('locales.supported', ['en', 'ru', 'kk']);
    $localeNames = config('locales.names', [
        'en' => 'English',
        'ru' => 'Русский',
        'kk' => 'Қазақша'
    ]);
    $localeFlags = config('locales.flags', [
        'en' => '🇺🇸',
        'ru' => '🇷🇺',
        'kk' => '🇰🇿'
    ]);
@endphp

<div class="language-switcher dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $localeFlags[$currentLocale] ?? '🌐' }} {{ $localeNames[$currentLocale] ?? strtoupper($currentLocale) }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        @foreach($supportedLocales as $locale)
            @if($locale !== $currentLocale)
                <li>
                    @php
                        $targetUrl = null;
                        $route = request()->route();
                        if ($route && $route->getName()) {
                            $params = request()->route()->parameters();
                            $params['locale'] = $locale;
                            $targetUrl = route($route->getName(), $params);
                            // сохраняем query string
                            if (request()->getQueryString()) {
                                $targetUrl .= '?' . request()->getQueryString();
                            }
                        } else {
                            // Фолбэк: заменить первый сегмент (локаль) в текущем пути
                            $path = request()->path();
                            $segments = explode('/', $path);
                            if (!empty($segments)) {
                                $segments[0] = $locale;
                            }
                            $newPath = implode('/', $segments);
                            $targetUrl = url($newPath);
                            if (request()->getQueryString()) {
                                $targetUrl .= '?' . request()->getQueryString();
                            }
                        }
                    @endphp
                    <a class="dropdown-item" href="{{ $targetUrl }}">
                        {{ $localeFlags[$locale] ?? '🌐' }} {{ $localeNames[$locale] ?? strtoupper($locale) }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
