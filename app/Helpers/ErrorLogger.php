<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorLogger
{
    /**
     * Log an exception with detailed data
     *
     * @param string $message
     * @param Throwable $e
     * @return void
     */
    public static function log(string $message, Throwable $e): void
    {
        Log::error($message, [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->take(5)->toArray(),
        ]);
    }
}
