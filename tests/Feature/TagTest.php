<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tag feature tests.
 */
class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test can list tags.
     */
    public function test_can_list_tags(): void
    {
        $user = User::factory()->create();
        Tag::factory()->count(5)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/tags');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'tags',
                ],
            ]);
    }

    /**
     * Test can search tags.
     */
    public function test_can_search_tags(): void
    {
        $user = User::factory()->create();
        Tag::factory()->create(['name' => 'Laravel']);
        Tag::factory()->create(['name' => 'Vue.js']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/tags?search=Laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.tags');
    }

    /**
     * Test can create tag.
     */
    public function test_can_create_tag(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/tags', [
                'name' => 'New Tag',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'tag' => [
                        'id',
                        'name',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'New Tag',
        ]);
    }

    /**
     * Test creating duplicate tag returns existing.
     */
    public function test_creating_duplicate_tag_returns_existing(): void
    {
        $user = User::factory()->create();
        $existingTag = Tag::factory()->create(['name' => 'Existing Tag']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/tags', [
                'name' => 'Existing Tag',
            ]);

        $response->assertStatus(201);

        $this->assertEquals($existingTag->id, $response->json('data.tag.id'));
    }

    /**
     * Test tag validation.
     */
    public function test_tag_validation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/tags', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
