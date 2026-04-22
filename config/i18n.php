<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | List of available locales with their display names and RTL support
    |
    */

    'locales' => [
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'rtl' => false,
            'flag' => '🇺🇸',
        ],
        'id' => [
            'name' => 'Indonesian',
            'native' => 'Bahasa Indonesia',
            'rtl' => false,
            'flag' => '🇮🇩',
        ],
        'ar' => [
            'name' => 'Arabic',
            'native' => 'العربية',
            'rtl' => true,
            'flag' => '🇸🇦',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | The default locale that will be used when no locale is specified
    |
    */

    'default_locale' => env('APP_LOCALE', 'id'),

    /*
    |--------------------------------------------------------------------------
    | Available Currencies
    |--------------------------------------------------------------------------
    |
    | List of available currencies with their display information
    |
    */

    'currencies' => [
        'IDR' => [
            'name' => 'Indonesian Rupiah',
            'symbol' => 'Rp',
            'code' => 'IDR',
            'decimal_places' => 0,
        ],
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'code' => 'USD',
            'decimal_places' => 2,
        ],
        'EUR' => [
            'name' => 'Euro',
            'symbol' => '€',
            'code' => 'EUR',
            'decimal_places' => 2,
        ],
        'SAR' => [
            'name' => 'Saudi Riyal',
            'symbol' => 'ر.س',
            'code' => 'SAR',
            'decimal_places' => 2,
        ],
        'AED' => [
            'name' => 'UAE Dirham',
            'symbol' => 'د.إ',
            'code' => 'AED',
            'decimal_places' => 2,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency that will be used when no currency is specified
    |
    */

    'default_currency' => env('APP_CURRENCY', 'IDR'),

    /*
    |--------------------------------------------------------------------------
    | Available Timezones
    |--------------------------------------------------------------------------
    |
    | List of common timezones grouped by region
    |
    */

    'timezones' => [
        'Asia' => [
            'Asia/Jakarta' => 'Jakarta (WIB)',
            'Asia/Makassar' => 'Makassar (WITA)',
            'Asia/Jayapura' => 'Jayapura (WIT)',
            'Asia/Singapore' => 'Singapore',
            'Asia/Kuala_Lumpur' => 'Kuala Lumpur',
            'Asia/Bangkok' => 'Bangkok',
            'Asia/Manila' => 'Manila',
            'Asia/Hong_Kong' => 'Hong Kong',
            'Asia/Tokyo' => 'Tokyo',
            'Asia/Seoul' => 'Seoul',
            'Asia/Dubai' => 'Dubai',
            'Asia/Riyadh' => 'Riyadh',
        ],
        'Middle East' => [
            'Asia/Dubai' => 'Dubai (UAE)',
            'Asia/Riyadh' => 'Riyadh (Saudi Arabia)',
            'Asia/Kuwait' => 'Kuwait',
            'Asia/Bahrain' => 'Bahrain',
            'Asia/Qatar' => 'Qatar',
        ],
        'Europe' => [
            'Europe/London' => 'London',
            'Europe/Paris' => 'Paris',
            'Europe/Berlin' => 'Berlin',
            'Europe/Rome' => 'Rome',
            'Europe/Madrid' => 'Madrid',
        ],
        'Americas' => [
            'America/New_York' => 'New York',
            'America/Chicago' => 'Chicago',
            'America/Denver' => 'Denver',
            'America/Los_Angeles' => 'Los Angeles',
            'America/Sao_Paulo' => 'Sao Paulo',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Timezone
    |--------------------------------------------------------------------------
    |
    | The default timezone that will be used when no timezone is specified
    |
    */

    'default_timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Date Formats by Locale
    |--------------------------------------------------------------------------
    |
    | Date formats for different locales
    |
    */

    'date_formats' => [
        'en' => [
            'date' => 'M d, Y',
            'datetime' => 'M d, Y H:i',
            'time' => 'H:i',
        ],
        'id' => [
            'date' => 'd M Y',
            'datetime' => 'd M Y H:i',
            'time' => 'H:i',
        ],
        'ar' => [
            'date' => 'Y/m/d',
            'datetime' => 'Y/m/d H:i',
            'time' => 'H:i',
        ],
    ],
];
