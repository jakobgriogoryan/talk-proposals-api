<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProposalStatus;
use App\Enums\UserRole;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Admin proposal feature tests.
 */
class AdminProposalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can view all proposals.
     */
    public function test_admin_can_view_all_proposals(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        Proposal::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/proposals');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'proposals',
                    'pagination',
                ],
            ]);
    }

    /**
     * Test non-admin cannot access admin endpoints.
     */
    public function test_non_admin_cannot_access_admin_endpoints(): void
    {
        $speaker = User::factory()->create(['role' => UserRole::SPEAKER->value]);

        $response = $this->actingAs($speaker, 'sanctum')
            ->getJson('/api/admin/proposals');

        $response->assertStatus(403);
    }

    /**
     * Test admin can filter proposals by status.
     */
    public function test_admin_can_filter_proposals_by_status(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        Proposal::factory()->create(['status' => ProposalStatus::PENDING->value]);
        Proposal::factory()->create(['status' => ProposalStatus::APPROVED->value]);
        Proposal::factory()->create(['status' => ProposalStatus::REJECTED->value]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/proposals?status='.ProposalStatus::APPROVED->value);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.proposals'));
    }

    /**
     * Test admin can filter proposals by user.
     */
    public function test_admin_can_filter_proposals_by_user(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $user = User::factory()->create();
        Proposal::factory()->create(['user_id' => $user->id]);
        Proposal::factory()->count(2)->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/proposals?user_id='.$user->id);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.proposals'));
    }

    /**
     * Test admin can update proposal status.
     */
    public function test_admin_can_update_proposal_status(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $proposal = Proposal::factory()->create(['status' => ProposalStatus::PENDING->value]);

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/admin/proposals/{$proposal->id}/status", [
                'status' => ProposalStatus::APPROVED->value,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('proposals', [
            'id' => $proposal->id,
            'status' => ProposalStatus::APPROVED->value,
        ]);
    }

    /**
     * Test admin cannot set invalid status.
     */
    public function test_admin_cannot_set_invalid_status(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/admin/proposals/{$proposal->id}/status", [
                'status' => 'invalid',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
