<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency
     *
     * @param float $amount
     * @param string|null $currency
     * @return string
     */
    function format_currency(float $amount, ?string $currency = null): string
    {
        $currency = $currency ?? Session::get('currency')
            ?? (Auth::check() && Auth::user() ? (Auth::user()->currency ?? null) : null)
            ?? config('i18n.default_currency', 'IDR');

        $currencyInfo = config("i18n.currencies.{$currency}");

        if (!$currencyInfo) {
            return number_format($amount, 2);
        }

        $symbol = $currencyInfo['symbol'];
        $decimalPlaces = $currencyInfo['decimal_places'] ?? 2;
        $formatted = number_format($amount, $decimalPlaces);

        // RTL currencies (Arabic, etc.) put symbol on the right
        $isRTL = in_array($currency, ['SAR', 'AED']);

        if ($isRTL) {
            return $formatted . ' ' . $symbol;
        }

        return $symbol . ' ' . $formatted;
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date according to current locale
     *
     * @param \Carbon\Carbon|string|null $date
     * @param string $format
     * @return string
     */
    function format_date($date, string $format = 'date'): string
    {
        if (!$date) {
            return '';
        }

        try {
            if (is_string($date)) {
                $date = \Carbon\Carbon::parse($date);
            }

            // Ensure $date is Carbon instance
            if (!$date instanceof \Carbon\Carbon) {
                return '';
            }

            $locale = app()->getLocale();
            $dateFormats = config("i18n.date_formats.{$locale}", config('i18n.date_formats.id', []));

            if (!is_array($dateFormats) || empty($dateFormats)) {
                // Fallback to default format
                $formatString = $format === 'datetime' ? 'Y-m-d H:i' : ($format === 'time' ? 'H:i' : 'Y-m-d');
            } else {
                $formatString = $dateFormats[$format] ?? ($dateFormats['date'] ?? 'Y-m-d');
            }

            return $date->format($formatString);
        } catch (\Exception $e) {
            // Return empty string or formatted date with default format if parsing fails
            Log::warning('Date formatting error: ' . $e->getMessage());
            return '';
        }
    }
}

if (!function_exists('get_user_timezone')) {
    /**
     * Get user's timezone
     *
     * @return string
     */
    function get_user_timezone(): string
    {
        return Session::get('timezone')
            ?? (Auth::check() && Auth::user() ? (Auth::user()->timezone ?? null) : null)
            ?? config('i18n.default_timezone', 'Asia/Jakarta');
    }
}

if (!function_exists('convert_to_user_timezone')) {
    /**
     * Convert datetime to user's timezone
     *
     * @param \Carbon\Carbon|string $datetime
     * @return \Carbon\Carbon
     */
    function convert_to_user_timezone($datetime)
    {
        try {
            if (is_string($datetime)) {
                $datetime = \Carbon\Carbon::parse($datetime);
            }

            // Ensure $datetime is Carbon instance
            if (!$datetime instanceof \Carbon\Carbon) {
                return \Carbon\Carbon::now();
            }

            $userTimezone = get_user_timezone();

            // Validate timezone before setting
            try {
                new \DateTimeZone($userTimezone);
                return $datetime->setTimezone($userTimezone);
            } catch (\Exception $e) {
                Log::warning('Invalid timezone: ' . $userTimezone . ' - ' . $e->getMessage());
                // Return with default timezone
                return $datetime->setTimezone(config('i18n.default_timezone', 'Asia/Jakarta'));
            }
        } catch (\Exception $e) {
            Log::warning('Timezone conversion error: ' . $e->getMessage());
            // Return current time with default timezone
            return \Carbon\Carbon::now()->setTimezone(config('i18n.default_timezone', 'Asia/Jakarta'));
        }
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     *
     * @return bool
     */
    function is_rtl(): bool
    {
        $locale = app()->getLocale();
        $localeInfo = config("i18n.locales.{$locale}");

        return $localeInfo['rtl'] ?? false;
    }
}

if (!function_exists('get_available_locales')) {
    /**
     * Get available locales
     *
     * @return array
     */
    function get_available_locales(): array
    {
        return config('i18n.locales', []);
    }
}

if (!function_exists('get_available_currencies')) {
    /**
     * Get available currencies
     *
     * @return array
     */
    function get_available_currencies(): array
    {
        return config('i18n.currencies', []);
    }
}

if (!function_exists('get_available_timezones')) {
    /**
     * Get available timezones
     *
     * @return array
     */
    function get_available_timezones(): array
    {
        return config('i18n.timezones', []);
    }
}
