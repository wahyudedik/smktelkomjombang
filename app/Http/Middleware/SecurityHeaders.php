<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk menambahkan security headers pada setiap response.
 *
 * Headers yang ditambahkan:
 * - X-Frame-Options: Mencegah clickjacking
 * - X-Content-Type-Options: Mencegah MIME type sniffing
 * - X-XSS-Protection: Legacy XSS protection untuk browser lama
 * - Referrer-Policy: Kontrol referrer information
 * - Permissions-Policy: Kontrol fitur browser
 * - Content-Security-Policy: Dasar CSP untuk XSS prevention
 */
class SecurityHeaders
{
    /**
     * Handle incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Hanya tambahkan headers untuk HTML responses (bukan API/JSON/redirect)
        if ($this->shouldAddHeaders($request, $response)) {
            $this->addSecurityHeaders($response);
        }

        return $response;
    }

    /**
     * Tentukan apakah security headers perlu ditambahkan.
     */
    private function shouldAddHeaders(Request $request, Response $response): bool
    {
        // Skip untuk API responses
        if ($request->expectsJson() || $request->is('api/*')) {
            return false;
        }

        // Skip untuk Instagram webhook dan iClock endpoints
        if ($request->is('instagram/*') || $request->is('iclock/*')) {
            return false;
        }

        // Skip untuk文件下载 responses
        $contentType = $response->headers->get('Content-Type', '');
        if (
            str_contains($contentType, 'application/pdf') ||
            str_contains($contentType, 'application/zip') ||
            str_contains($contentType, 'application/vnd.') ||
            str_contains($contentType, 'image/') ||
            str_contains($contentType, 'text/csv')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Tambahkan semua security headers ke response.
     */
    private function addSecurityHeaders(Response $response): void
    {
        // X-Frame-Options: DENY mencegah iframe embedding (clickjacking prevention)
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options: nosniff mencegah MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: Legacy XSS filter untuk browser lama (IE, Chrome lama)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Kirim referrer hanya untuk same-origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: Disable fitur yang tidak diperlukan
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()'
        );

        // Content-Security-Policy: Dasar CSP
        // Catatan: CSP yang terlalu ketat bisa memecah fitur yang ada.
        // Di development, skip CSP karena Vite dev server membutuhkan akses yang luas.
        if (!app()->environment('local', 'development')) {
            $cspDirectives = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.bunny.net",
                "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.bunny.net",
                "img-src 'self' data: blob: https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.bunny.net",
                "font-src 'self' data: https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.bunny.net",
                "connect-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ];

            $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
        }

        // Strict-Transport-Security: HSTS (hanya untuk HTTPS)
        if ($this->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // X-Permitted-Cross-Domain-Policies: Mencegah Flash/PDF cross-domain
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Cache-Control untuk HTML responses: no-store untuk mencegah caching sensitif
        $contentType = $response->headers->get('Content-Type', '');
        if (str_contains($contentType, 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }
    }

    /**
     * Cek apakah request menggunakan HTTPS.
     */
    private function isSecure(): bool
    {
        return request()->secure();
    }
}
