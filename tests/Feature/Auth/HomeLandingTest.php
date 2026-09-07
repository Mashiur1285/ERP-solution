<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class HomeLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_dashboard_access_is_sent_to_a_permitted_module(): void
    {
        $permission = Permission::create([
            'name' => 'sales.add',
            'guard_name' => 'web',
        ]);
        $user = User::factory()->create();
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertRedirect(route('sales.index', absolute: false));
    }

    public function test_user_with_no_permissions_gets_a_clear_no_access_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $this->assertSame('Auth/NoAccess', $response->viewData('page')['component']);
    }

    public function test_authenticated_users_are_not_redirected_to_dashboard_from_guest_pages(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('home', absolute: false));
    }

    public function test_direct_dashboard_access_without_permission_is_sent_to_home(): void
    {
        $permission = Permission::create([
            'name' => 'sales.add',
            'guard_name' => 'web',
        ]);
        $user = User::factory()->create();
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('home', absolute: false));
    }

    public function test_dashboard_permission_still_lands_on_the_dashboard(): void
    {
        $permission = Permission::create([
            'name' => 'dashboard.view',
            'guard_name' => 'web',
        ]);
        $user = User::factory()->create();
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
