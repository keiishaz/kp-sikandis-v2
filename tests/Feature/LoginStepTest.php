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

    public function test_login_admin_redirects_to_admin_dashboard()
    {
        $adminRole = \App\Models\Role::where('nama_role', 'admin')->first();
        $user = User::factory()->create([
            'nip' => '123456789',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id
        ]);

        // Step 1: Check NIP
        $this->post('/login-nip', ['nip' => '123456789'])
             ->assertRedirect('/login-password')
             ->assertSessionHas('login_nip', '123456789');

        // Step 2: Submit Password
        $this->post('/login-password', ['password' => 'password'])
             ->assertRedirect(route('admin.dashboard'));
             
        $this->assertAuthenticatedAs($user);
        
        // Check Log
        $log = File::get(storage_path('logs/login.txt'));
        $this->assertStringContainsString('LOGIN SUCCESS', $log);
    }

    public function test_login_operator_redirects_to_operator_dashboard()
    {
        $operatorRole = \App\Models\Role::where('nama_role', 'operator')->first();
        $user = User::factory()->create([
            'nip' => '987654321',
            'password' => Hash::make('password'),
            'role_id' => $operatorRole->id
        ]);

        session(['login_nip' => $user->nip]);

        // Step 2: Submit Password
        $this->post('/login-password', ['password' => 'password'])
             ->assertRedirect(route('operator.dashboard'));
             
        $this->assertAuthenticatedAs($user);
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

    public function test_authenticated_user_cannot_access_login_page()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $this->actingAs($user);

        $this->get('/login')->assertRedirect(route('dashboard'));
        $this->get('/login-password')->assertRedirect(route('dashboard'));
    }

    public function test_admin_cannot_access_operator_dashboard()
    {
        $adminRole = \App\Models\Role::where('nama_role', 'admin')->first();
        $user = User::factory()->create(['nip' => '111111', 'role_id' => $adminRole->id]);
        $this->actingAs($user);

        // Should redirect to admin dashboard
        $this->get(route('operator.dashboard'))->assertRedirect(route('admin.dashboard'));
    }

    public function test_operator_cannot_access_admin_dashboard()
    {
        $operatorRole = \App\Models\Role::where('nama_role', 'operator')->first();
        $user = User::factory()->create(['nip' => '222222', 'role_id' => $operatorRole->id]);
        $this->actingAs($user);

        // Should redirect to operator dashboard
        $this->get(route('admin.dashboard'))->assertRedirect(route('operator.dashboard'));
    }

    public function test_logout_functionality()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $this->actingAs($user);

        $this->post(route('logout'))
             ->assertRedirect('/');

        $this->assertGuest();
    }
}
