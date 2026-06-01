<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $context = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ];

            if (auth()->check()) {
                $context['user_id'] = auth()->id();
                $context['user_email'] = auth()->user()->email;
            }
            $context['url'] = request()->fullUrl();
            $context['method'] = request()->method();
            $context['ip'] = request()->ip();

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $level = $e->getStatusCode() >= 500 ? 'error' : 'warning';
                Log::channel('error')->$level($e->getMessage(), $context);
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                Log::channel('sql')->error('Database query exception: ' . $e->getMessage(), $context);
                Log::channel('error')->error('Database exception: ' . $e->getMessage(), $context);
            } else {
                Log::channel('error')->error($e->getMessage(), $context);
            }
        });
    }
}
