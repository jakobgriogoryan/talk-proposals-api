<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Proposal file feature tests.
 */
class ProposalFileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test speaker can download own proposal file.
     * Note: This test requires actual file storage, not fake storage.
     */
    public function test_speaker_can_download_own_proposal_file(): void
    {
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);

        // Create actual file in storage
        $filePath = 'proposals/test-proposal-'.time().'.pdf';
        Storage::disk('public')->put($filePath, 'fake pdf content');

        $proposal = Proposal::factory()->create([
            'user_id' => $speaker->id,
            'file_path' => $filePath,
        ]);

        // Use get() instead of getJson() for file downloads
        $response = $this->actingAs($speaker, 'sanctum')
            ->get("/api/proposals/{$proposal->id}/download");

        $response->assertStatus(200);

        // Cleanup
        Storage::disk('public')->delete($filePath);
    }

    /**
     * Test speaker cannot download other speaker's file.
     */
    public function test_speaker_cannot_download_other_speaker_file(): void
    {
        Storage::fake('public');
        $speaker1 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $speaker2 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $file = UploadedFile::fake()->create('proposal.pdf', 100);
        $filePath = $file->store('proposals', 'public');
        $proposal = Proposal::factory()->create([
            'user_id' => $speaker2->id,
            'file_path' => $filePath,
        ]);

        $response = $this->actingAs($speaker1, 'sanctum')
            ->getJson("/api/proposals/{$proposal->id}/download");

        $response->assertStatus(403);
    }

    /**
     * Test reviewer can download any proposal file.
     * Note: This test requires actual file storage, not fake storage.
     */
    public function test_reviewer_can_download_any_proposal_file(): void
    {
        $reviewer = User::factory()->create(['role' => UserRole::REVIEWER->value]);

        // Create actual file in storage
        $filePath = 'proposals/test-proposal-'.time().'.pdf';
        Storage::disk('public')->put($filePath, 'fake pdf content');

        $proposal = Proposal::factory()->create([
            'file_path' => $filePath,
        ]);

        // Use get() instead of getJson() for file downloads
        $response = $this->actingAs($reviewer, 'sanctum')
            ->get("/api/proposals/{$proposal->id}/download");

        $response->assertStatus(200);

        // Cleanup
        Storage::disk('public')->delete($filePath);
    }

    /**
     * Test download returns 404 when file not found.
     */
    public function test_download_returns_404_when_file_not_found(): void
    {
        $user = User::factory()->create(['role' => UserRole::REVIEWER->value]);
        $proposal = Proposal::factory()->create(['file_path' => null]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/proposals/{$proposal->id}/download");

        $response->assertStatus(404);
    }
}
