<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HttpLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $statusCode = $response->getStatusCode();

        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $statusCode,
            'duration' => "{$duration}ms",
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        if (auth()->check()) {
            $logData['user_id'] = auth()->id();
            $logData['user_email'] = auth()->user()->email;
        }

        if ($statusCode >= 500) {
            Log::channel('error')->error('HTTP 5xx Server Error', $logData);
            Log::channel('http')->error('HTTP Request Failed', $logData);
        } elseif ($statusCode >= 400) {
            Log::channel('error')->warning('HTTP 4xx Client Error', $logData);
            Log::channel('http')->warning('HTTP Request Warning', $logData);
        } else {
            Log::channel('http')->info('HTTP Request', $logData);
        }

        if ($duration > 2000) {
            Log::channel('sql')->warning('Slow HTTP request', $logData);
        }

        return $response;
    }
}
