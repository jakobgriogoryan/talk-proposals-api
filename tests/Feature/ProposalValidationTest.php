<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Proposal validation feature tests.
 */
class ProposalValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test proposal creation requires title.
     */
    public function test_proposal_creation_requires_title(): void
    {
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);

        $response = $this->actingAs($speaker, 'sanctum')
            ->postJson('/api/proposals', [
                'description' => 'Test Description',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test proposal creation requires description.
     */
    public function test_proposal_creation_requires_description(): void
    {
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);

        $response = $this->actingAs($speaker, 'sanctum')
            ->postJson('/api/proposals', [
                'title' => 'Test Title',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
    }

    /**
     * Test proposal file must be PDF.
     */
    public function test_proposal_file_must_be_pdf(): void
    {
        Storage::fake('public');
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $file = UploadedFile::fake()->create('document.txt', 100);

        $response = $this->actingAs($speaker, 'sanctum')
            ->postJson('/api/proposals', [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'file' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /**
     * Test proposal file size limit.
     */
    public function test_proposal_file_size_limit(): void
    {
        Storage::fake('public');
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $file = UploadedFile::fake()->create('proposal.pdf', 5000); // 5MB

        $response = $this->actingAs($speaker, 'sanctum')
            ->postJson('/api/proposals', [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'file' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /**
     * Test proposal tags validation.
     */
    public function test_proposal_tags_validation(): void
    {
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);

        $response = $this->actingAs($speaker, 'sanctum')
            ->postJson('/api/proposals', [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'tags' => ['Valid Tag', ''], // Empty tag
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tags.1']);
    }
}
