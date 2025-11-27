<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ReviewRating;
use App\Enums\UserRole;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Review validation feature tests.
 */
class ReviewValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test review creation requires rating.
     */
    public function test_review_creation_requires_rating(): void
    {
        $reviewer = User::factory()->create(['role' => UserRole::REVIEWER->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($reviewer, 'sanctum')
            ->postJson("/api/proposals/{$proposal->id}/reviews", [
                'comment' => 'Test comment',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rating']);
    }

    /**
     * Test review rating must be valid.
     */
    public function test_review_rating_must_be_valid(): void
    {
        $reviewer = User::factory()->create(['role' => UserRole::REVIEWER->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($reviewer, 'sanctum')
            ->postJson("/api/proposals/{$proposal->id}/reviews", [
                'rating' => 6, // Invalid rating
                'comment' => 'Test comment',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rating']);
    }

    /**
     * Test review comment is optional.
     */
    public function test_review_comment_is_optional(): void
    {
        $reviewer = User::factory()->create(['role' => UserRole::REVIEWER->value]);
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($reviewer, 'sanctum')
            ->postJson("/api/proposals/{$proposal->id}/reviews", [
                'rating' => ReviewRating::FIVE->value,
            ]);

        $response->assertStatus(201);
    }
}
