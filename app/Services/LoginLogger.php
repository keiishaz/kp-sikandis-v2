<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LoginLogger
{
    protected static $logFile = 'logs/login.txt';

    public static function log($status, $nip, $context = [])
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $message = "[{$timestamp}] {$status} — {$nip}";

        if (!empty($context)) {
            $contextStr = json_encode($context);
            $message .= " {$contextStr}";
        }

        $path = storage_path(self::$logFile);
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::append($path, $message . PHP_EOL);
    }
}
