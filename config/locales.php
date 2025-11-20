<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | This value determines which locales are supported by the application.
    | The first locale in the array will be used as the fallback locale.
    |
    */
    'supported' => ['en', 'ru', 'kk'],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | This value determines the default locale that will be used when
    | the application starts or when no locale is specified.
    |
    */
    'default' => 'ru',

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | This value determines the fallback locale that will be used when
    | the requested locale is not available.
    |
    */
    'fallback' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Locale Names
    |--------------------------------------------------------------------------
    |
    | These are the human-readable names for each supported locale.
    | Used in language switchers and UI elements.
    |
    */
    'names' => [
        'en' => 'English',
        'ru' => 'Русский',
        'kk' => 'Қазақша',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Flags (for UI)
    |--------------------------------------------------------------------------
    |
    | Flag icons or CSS classes for each locale.
    | Used in language switchers.
    |
    */
    'flags' => [
        'en' => '🇺🇸',
        'ru' => '🇷🇺',
        'kk' => '🇰🇿',
    ],
];
