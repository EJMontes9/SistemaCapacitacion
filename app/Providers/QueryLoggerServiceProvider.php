<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (!config('app.debug')) {
            return;
        }

        DB::listen(function ($query) {
            $sql = $query->sql;
            $bindings = $query->bindings;
            $time = $query->time;

            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('Y-m-d H:i:s');
                } elseif (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                } elseif ($binding === null) {
                    $bindings[$i] = 'NULL';
                }
            }

            $sql = str_replace(['%', '?'], ['%%', '%s'], $sql);
            $fullSql = vsprintf($sql, $bindings);

            Log::channel('query')->debug('SQL Query', [
                'sql' => $fullSql,
                'time' => "{$time}ms",
                'connection' => $query->connectionName,
            ]);

            if ($time > 500) {
                Log::channel('sql')->warning('Slow query detected', [
                    'sql' => $fullSql,
                    'time' => "{$time}ms",
                    'connection' => $query->connectionName,
                ]);
            }
        });

        DB::whenQueryingForLongerThan(1000, function ($connection, $time) {
            Log::channel('sql')->error('Extremely slow query', [
                'time' => "{$time}ms",
                'connection' => $connection->getName(),
            ]);
        });
    }
}
