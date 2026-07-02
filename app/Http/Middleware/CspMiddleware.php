<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CspMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a random nonce for HTML attributes (tag-level)
        $nonce = Str::random(32);

        // Share nonce with all views so tags like nonce="{{ $cspNonce }}" work
        view()->share('cspNonce', $nonce);

        /** @var Response $response */
        $response = $next($request);

        // Only add CSP header to HTML responses (not JSON API responses)
        if ($response->headers->has('Content-Type') && 
            str_contains($response->headers->get('Content-Type') ?? '', 'text/html')) {
            
            $cspHeader = $this->buildCspHeader();
            
            // Remove any existing CSP headers first (from Apache/XAMPP server config)
            $response->headers->remove('Content-Security-Policy');
            
            // Set our own CSP header
            $response->headers->set('Content-Security-Policy', $cspHeader);
        }

        return $response;
    }

    /**
     * Build the Content-Security-Policy header value.
     *
     * NOTE: 'unsafe-inline' is used here because Midtrans Snap dynamically
     * injects inline scripts and styles at runtime (via snap.js), which
     * cannot be pre-covered by a nonce. When a nonce is present in a CSP
     * directive, the browser ignores 'unsafe-inline' per CSP specification,
     * so we intentionally omit the nonce from the CSP header directives.
     * The nonce attribute on HTML <script>/<style> tags is kept for
     * documentation / future migration purposes.
     */
    private function buildCspHeader(): string
    {
        $scriptSources = [
            "'self'",
            "'unsafe-inline'",
            // Midtrans Snap
            'https://app.sandbox.midtrans.com',
            'https://snap-assets.sandbox.midtrans.com',
            'https://api.sandbox.midtrans.com',
            // Payment gateways / third-party
            'https://pay.google.com',
            'https://gwk.gopayapi.com/sdk/stable/gp-container.min.js',
            'https://www.googletagmanager.com',
            // CDN for Bootstrap, jQuery, etc.
            'https://cdn.jsdelivr.net',
            'https://code.jquery.com',
            // Google Fonts
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
        ];

        $styleSources = [
            "'self'",
            "'unsafe-inline'",
            'https://cdn.jsdelivr.net',
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
        ];

        $imgSources = [
            "'self'",
            'data:',
            'https://*.midtrans.com',
            'https://app.sandbox.midtrans.com',
            'https://snap-assets.sandbox.midtrans.com',
            'https://api.sandbox.midtrans.com',
            'https://pay.google.com',
            'https://www.gstatic.com',
            'https://www.google.com',
            'https://fonts.gstatic.com',
        ];

        $frameSources = [
            "'self'",
            'https://app.sandbox.midtrans.com',
            'https://snap-assets.sandbox.midtrans.com',
        ];

        $connectSources = [
            "'self'",
            'https://api.sandbox.midtrans.com',
            'https://app.sandbox.midtrans.com',
            'https://cdn.jsdelivr.net',
            'https://code.jquery.com',
        ];

        $fontSources = [
            "'self'",
            'https://cdn.jsdelivr.net',
            'https://fonts.gstatic.com',
            'https://fonts.googleapis.com',
        ];

        return implode('; ', [
            "script-src " . implode(' ', $scriptSources),
            "style-src " . implode(' ', $styleSources),
            "img-src " . implode(' ', $imgSources),
            "frame-src " . implode(' ', $frameSources),
            "connect-src " . implode(' ', $connectSources),
            "font-src " . implode(' ', $fontSources),
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }
}