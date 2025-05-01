<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\Auth;

class ErrorLogger
{
    public static function log(string $context, Throwable $e): void
    {
        Log::error($context, [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);
    }
}