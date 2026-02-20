<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\LoginLogger;

// Ensure a test user exists
$nip = '999999999';
$password = 'password123';
$user = User::firstOrCreate(
    ['nip' => $nip],
    ['name' => 'Test User', 'password' => Hash::make($password), 'role_id' => 1]
);

// clear log
file_put_contents(storage_path('logs/login.txt'), '');

echo "--- Starting Verification ---\n";

// 1. Simulate Check NIP (Success)
echo "1. Check NIP Success... ";
$user->locked_until = null;
$user->save();
// logic from controller
LoginLogger::log('TEST', 'Reset User');

// 2. Simulate Wrong Password 3 times
echo "\n2. Simulate 3 Wrong Passwords... \n";
for ($i = 1; $i <= 3; $i++) {
    echo "   Attempt $i: ";
    // Emulate Fortify logic
    $user->increment('failed_login_attempts');
    if ($user->failed_login_attempts >= 3) {
        $user->forceFill(['locked_until' => now()->addMinute()])->save();
        LoginLogger::log('LOGIN LOCKED 1 MIN', $user->nip);
        echo "LOCKED\n";
    } else {
        LoginLogger::log('LOGIN FAIL PASSWORD', $user->nip);
        echo "FAIL\n";
    }
}

// 3. Simulate Blocked Login
echo "3. Simulate Blocked Attempt... ";
if ($user->locked_until && now()->lt($user->locked_until)) {
    LoginLogger::log('LOGIN BLOCKED', $user->nip);
    echo "BLOCKED LOGGED\n";
} else {
    echo "FAILED TO BLOCK\n";
}

// 4. Simulate Success
echo "4. Simulate Success (Reset)... ";
$user->forceFill([
    'failed_login_attempts' => 0,
    'locked_until' => null,
    'last_login_at' => now(),
])->save();
LoginLogger::log('LOGIN SUCCESS', $user->nip);
echo "SUCCESS LOGGED\n";

echo "\n--- Reading Log File ---\n";
echo file_get_contents(storage_path('logs/login.txt'));
