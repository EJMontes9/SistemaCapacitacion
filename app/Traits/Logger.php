<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait Logger
{
    protected string $logChannel = 'audit';

    public function logInfo(string $message, array $context = []): void
    {
        $this->addContext($context);
        Log::channel($this->logChannel)->info($message, $context);
    }

    public function logWarning(string $message, array $context = []): void
    {
        $this->addContext($context);
        Log::channel($this->logChannel)->warning($message, $context);
    }

    public function logError(string $message, array $context = []): void
    {
        $this->addContext($context);
        Log::channel('error')->error($message, $context);
        Log::channel($this->logChannel)->error($message, $context);
    }

    public function logQuery(string $message, array $context = []): void
    {
        $this->addContext($context);
        Log::channel('query')->debug($message, $context);
    }

    public function logAudit(string $action, string $description, array $context = []): void
    {
        $this->addContext($context);
        $context['action'] = $action;
        $context['description'] = $description;
        Log::channel('audit')->info("AUDIT: {$action}", $context);
    }

    private function addContext(array &$context): void
    {
        if (auth()->check()) {
            $context['user_id'] ??= auth()->id();
            $context['user_email'] ??= auth()->user()->email;
        }
        $context['ip'] ??= request()->ip();
        $context['url'] ??= request()->fullUrl();
        $context['method'] ??= request()->method();
    }
}
