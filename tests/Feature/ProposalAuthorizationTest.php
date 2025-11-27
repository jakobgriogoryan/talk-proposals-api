<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Proposal authorization feature tests.
 */
class ProposalAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test speaker cannot view other speaker's proposal.
     */
    public function test_speaker_cannot_view_other_speaker_proposal(): void
    {
        $speaker1 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $speaker2 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $proposal = Proposal::factory()->create(['user_id' => $speaker2->id]);

        $response = $this->actingAs($speaker1, 'sanctum')
            ->getJson("/api/proposals/{$proposal->id}");

        $response->assertStatus(403);
    }

    /**
     * Test reviewer can view any proposal.
     */
    public function test_reviewer_can_view_any_proposal(): void
    {
        $reviewer = User::factory()->create(['role' => UserRole::REVIEWER->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($reviewer, 'sanctum')
            ->getJson("/api/proposals/{$proposal->id}");

        $response->assertStatus(200);
    }

    /**
     * Test speaker cannot update other speaker's proposal.
     */
    public function test_speaker_cannot_update_other_speaker_proposal(): void
    {
        $speaker1 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $speaker2 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $proposal = Proposal::factory()->create(['user_id' => $speaker2->id]);

        $response = $this->actingAs($speaker1, 'sanctum')
            ->putJson("/api/proposals/{$proposal->id}", [
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test speaker cannot delete other speaker's proposal.
     */
    public function test_speaker_cannot_delete_other_speaker_proposal(): void
    {
        $speaker1 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $speaker2 = User::factory()->create(['role' => UserRole::SPEAKER->value]);
        $proposal = Proposal::factory()->create(['user_id' => $speaker2->id]);

        $response = $this->actingAs($speaker1, 'sanctum')
            ->deleteJson("/api/proposals/{$proposal->id}");

        $response->assertStatus(403);
    }

    /**
     * Test admin can update any proposal.
     */
    public function test_admin_can_update_any_proposal(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/proposals/{$proposal->id}", [
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test admin can delete any proposal.
     */
    public function test_admin_can_delete_any_proposal(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/proposals/{$proposal->id}");

        $response->assertStatus(200);
    }
}
