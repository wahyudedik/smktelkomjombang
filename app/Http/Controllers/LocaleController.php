<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LocaleController extends Controller
{
    /**
     * Switch application locale
     */
    public function switchLocale(Request $request, string $locale)
    {
        $availableLocales = array_keys(config('i18n.locales', []));

        if (!in_array($locale, $availableLocales)) {
            return redirect()->back()->with('error', __('common.invalid'));
        }

        // IMPORTANT: Set session FIRST, then update user preference
        // This ensures the session is set immediately and takes priority in middleware
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Save to session immediately to ensure it persists
        Session::save();

        // Update user preference if logged in (for persistence across sessions)
        if (Auth::check() && ($user = Auth::user()) && $user instanceof User) {
            try {
                $user->update(['locale' => $locale]);
                // Refresh the user in session to get updated locale
                Auth::setUser($user->fresh());
            } catch (\Exception $e) {
                // Silently fail if field doesn't exist (migration not run yet)
                Log::warning('Failed to update user locale: ' . $e->getMessage());
            }
        }

        // Get redirect URL and clean it from existing lang parameters
        $referer = $request->headers->get('referer');
        
        if ($referer) {
            // Parse URL to clean it
            $parsedUrl = parse_url($referer);
            $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
            $baseUrl .= isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $baseUrl .= isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
            
            // Parse query string and remove existing 'lang' parameter
            $queryParams = [];
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
            }
            
            // Remove all lang parameters (in case there are duplicates)
            $queryParams = array_filter($queryParams, function($key) {
                return $key !== 'lang';
            }, ARRAY_FILTER_USE_KEY);
            
            // Add new lang parameter
            $queryParams['lang'] = $locale;
            
            // Rebuild URL with cleaned parameters
            $redirectUrl = $baseUrl . '?' . http_build_query($queryParams);
        } else {
            // Fallback to dashboard with lang parameter
            $redirectUrl = route('admin.dashboard', ['lang' => $locale]);
        }
        
        // Redirect to cleaned URL
        return redirect($redirectUrl)->with('success', __('common.updated_successfully'));
    }

    /**
     * Switch currency
     */
    public function switchCurrency(Request $request, string $currency)
    {
        $availableCurrencies = array_keys(config('i18n.currencies', []));

        if (!in_array($currency, $availableCurrencies)) {
            return redirect()->back()->with('error', __('common.invalid'));
        }

        Session::put('currency', $currency);

        // Update user preference if logged in
        if (Auth::check() && ($user = Auth::user()) && $user instanceof User) {
            try {
                $user->update(['currency' => $currency]);
            } catch (\Exception $e) {
                // Silently fail if field doesn't exist (migration not run yet)
                Log::warning('Failed to update user currency: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', __('common.updated_successfully'));
    }

    /**
     * Switch timezone
     */
    public function switchTimezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string',
        ]);

        $timezone = $request->input('timezone');

        // Validate timezone
        try {
            new \DateTimeZone($timezone);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('common.invalid'));
        }

        // IMPORTANT: Set session FIRST, then update user preference
        Session::put('timezone', $timezone);
        date_default_timezone_set($timezone);

        // Update user preference if logged in (for persistence across sessions)
        if (Auth::check() && ($user = Auth::user()) && $user instanceof User) {
            try {
                $user->update(['timezone' => $timezone]);
                // Refresh the user in session to get updated timezone
                Auth::setUser($user->fresh());
            } catch (\Exception $e) {
                // Silently fail if field doesn't exist (migration not run yet)
                Log::warning('Failed to update user timezone: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', __('common.updated_successfully'));
    }
}
