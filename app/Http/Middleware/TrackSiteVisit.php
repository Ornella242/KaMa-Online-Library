<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        SiteVisit::query()->create([
            'user_id' => $request->user()?->id,
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'path' => '/'.$request->path(),
            'route_name' => $request->route()?->getName(),
            'ip_hash' => $request->ip()
                ? hash_hmac('sha256', $request->ip(), (string) config('app.key'))
                : null,
        ]);

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (
            ! Schema::hasTable('site_visits')
            || ! $request->isMethod('GET')
            || $response->getStatusCode() >= 400
            || $request->expectsJson()
            || $request->is('admin', 'admin/*', 'writer', 'writer/*')
        ) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type');

        return str_contains($contentType, 'text/html');
    }
}
