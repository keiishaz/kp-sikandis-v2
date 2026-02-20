<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class LoginStepTest extends TestCase
{
    use RefreshDatabase; // Use transaction rollback for clean state

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Clear log file
        if(File::exists(storage_path('logs/login.txt'))){
            File::put(storage_path('logs/login.txt'), '');
        }
    }

    public function test_login_flow_success()
    {
        $user = User::factory()->create([
            'nip' => '123456789',
            'password' => Hash::make('password'),
            'role_id' => 1
        ]);

        // Step 1: Check NIP
        $response = $this->post('/login-nip', ['nip' => '123456789']);
        $response->assertRedirect('/login-password');
        $response->assertSessionHas('login_nip', '123456789');

        // Step 2: Submit Password
        $response = $this->post('/login-password', ['password' => 'password']);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        
        // Check Log
        $log = File::get(storage_path('logs/login.txt'));
        $this->assertStringContainsString('LOGIN SUCCESS', $log);
    }

    public function test_login_lockout_logic()
    {
        $user = User::factory()->create([
            'nip' => '987654321',
            'password' => Hash::make('password'),
            'role_id' => 1
        ]);

        session(['login_nip' => '987654321']);

        // Attempt 1
        $this->post('/login-password', ['password' => 'wrong'])->assertSessionHasErrors('password');
        
        // Attempt 2
        $this->post('/login-password', ['password' => 'wrong'])->assertSessionHasErrors('password');
        
        // Attempt 3 (Should Lock)
        $this->post('/login-password', ['password' => 'wrong'])->assertSessionHasErrors('password');
        
        $user->refresh();
        $this->assertNotNull($user->locked_until);
        
        // Check Log
        $log = File::get(storage_path('logs/login.txt'));
        $this->assertStringContainsString('LOGIN LOCKED 1 MIN', $log);

        // Attempt 4 (Blocked)
        $response = $this->post('/login-password', ['password' => 'correct_but_blocked']);
        $response->assertSessionHasErrors('password');
        
        // Check Log Blocked
        $log = File::get(storage_path('logs/login.txt'));
        $this->assertStringContainsString('LOGIN BLOCKED', $log);
    }
}
