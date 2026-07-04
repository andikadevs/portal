<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PexelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_reports_disabled_without_api_key(): void
    {
        config(['services.pexels.key' => null]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->getJson(route('admin.pexels.search', ['q' => 'teknologi']))
            ->assertOk()
            ->assertJson(['enabled' => false, 'photos' => []]);
    }

    public function test_search_returns_photos_with_api_key(): void
    {
        config(['services.pexels.key' => 'test-key']);
        $this->app->forgetInstance(\App\Services\PexelsService::class);

        Http::fake([
            'api.pexels.com/*' => Http::response([
                'photos' => [[
                    'id' => 123,
                    'alt' => 'Sebuah foto',
                    'photographer' => 'Fotografer',
                    'url' => 'https://pexels.com/photo/123',
                    'src' => [
                        'medium' => 'https://images.pexels.com/photos/123/m.jpeg',
                        'landscape' => 'https://images.pexels.com/photos/123/l.jpeg',
                    ],
                ]],
            ]),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->getJson(route('admin.pexels.search', ['q' => 'teknologi']))
            ->assertOk()
            ->assertJson([
                'enabled' => true,
                'photos' => [['id' => 123, 'full' => 'https://images.pexels.com/photos/123/l.jpeg']],
            ]);
    }

    public function test_search_requires_authentication(): void
    {
        $this->get(route('admin.pexels.search', ['q' => 'x']))->assertRedirect(route('login'));
    }
}
