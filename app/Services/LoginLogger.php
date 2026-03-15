<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LoginLogger
{
    public static function log($status, $nip, $context = [])
    {
        $month = now()->format('Y_m');
        $logFile = "logs/login_{$month}.txt";

        $timestamp = now()->format('Y-m-d H:i:s');
        $message = "[{$timestamp}] {$status} — {$nip}";

        if (!empty($context)) {
            $ip = $context['ip'] ?? '-';
            $ua = $context['user_agent'] ?? '';
            
            $os = 'Unknown OS';
            if (preg_match('/windows nt/i', $ua)) $os = 'Windows';
            elseif (preg_match('/mac os x/i', $ua)) $os = 'Mac OS';
            elseif (preg_match('/linux/i', $ua)) $os = 'Linux';
            elseif (preg_match('/android/i', $ua)) $os = 'Android';
            elseif (preg_match('/iphone|ipad/i', $ua)) $os = 'iOS';

            $browser = 'Unknown Browser';
            if (preg_match('/edg/i', $ua)) $browser = 'Edge';
            elseif (preg_match('/chrome|crios/i', $ua)) $browser = 'Chrome';
            elseif (preg_match('/firefox|fxios/i', $ua)) $browser = 'Firefox';
            elseif (preg_match('/safari/i', $ua)) $browser = 'Safari';

            $message .= " — IP: {$ip} — OS: {$os} — Browser: {$browser}";
        }

        $path = storage_path($logFile);
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::append($path, $message . PHP_EOL);
    }
}
