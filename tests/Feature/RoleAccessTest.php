<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_ketua_can_reach_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ketua = User::factory()->create(['role' => 'ketua']);

        $this->actingAs($admin)->get('/dashboard')->assertOk();
        $this->actingAs($ketua)->get('/dashboard')->assertOk();
    }

    public function test_admin_cannot_access_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.statistik'))->assertForbidden();
    }

    public function test_ketua_can_access_user_management(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua']);

        $this->actingAs($ketua)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($ketua)->get(route('admin.statistik'))->assertOk();
    }

    public function test_admin_can_manage_categories_and_articles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.categories.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.articles.index'))->assertOk();
    }
}
