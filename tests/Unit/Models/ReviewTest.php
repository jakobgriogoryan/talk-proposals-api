<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\ReviewRating;
use App\Models\Proposal;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Review model unit tests.
 */
class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test review belongs to proposal.
     */
    public function test_review_belongs_to_proposal(): void
    {
        $proposal = Proposal::factory()->create();
        $review = Review::factory()->create(['proposal_id' => $proposal->id]);

        $this->assertEquals($proposal->id, $review->proposal->id);
    }

    /**
     * Test review belongs to reviewer.
     */
    public function test_review_belongs_to_reviewer(): void
    {
        $reviewer = User::factory()->create();
        $review = Review::factory()->create(['reviewer_id' => $reviewer->id]);

        $this->assertEquals($reviewer->id, $review->reviewer->id);
    }

    /**
     * Test review scope by proposal.
     */
    public function test_review_scope_by_proposal(): void
    {
        $proposal1 = Proposal::factory()->create();
        $proposal2 = Proposal::factory()->create();
        Review::factory()->create(['proposal_id' => $proposal1->id]);
        Review::factory()->create(['proposal_id' => $proposal1->id]);
        Review::factory()->create(['proposal_id' => $proposal2->id]);

        $this->assertEquals(2, Review::byProposal($proposal1->id)->count());
        $this->assertEquals(1, Review::byProposal($proposal2->id)->count());
    }

    /**
     * Test review scope by reviewer.
     */
    public function test_review_scope_by_reviewer(): void
    {
        $reviewer1 = User::factory()->create();
        $reviewer2 = User::factory()->create();
        Review::factory()->create(['reviewer_id' => $reviewer1->id]);
        Review::factory()->create(['reviewer_id' => $reviewer1->id]);
        Review::factory()->create(['reviewer_id' => $reviewer2->id]);

        $this->assertEquals(2, Review::byReviewer($reviewer1->id)->count());
        $this->assertEquals(1, Review::byReviewer($reviewer2->id)->count());
    }

    /**
     * Test review scope by rating.
     */
    public function test_review_scope_by_rating(): void
    {
        Review::factory()->create(['rating' => ReviewRating::FIVE->value]);
        Review::factory()->create(['rating' => ReviewRating::FIVE->value]);
        Review::factory()->create(['rating' => ReviewRating::FOUR->value]);

        $this->assertEquals(2, Review::byRating(ReviewRating::FIVE->value)->count());
        $this->assertEquals(1, Review::byRating(ReviewRating::FOUR->value)->count());
    }

    /**
     * Test review rating validation.
     */
    public function test_review_rating_validation(): void
    {
        $this->assertTrue(Review::isValidRating(ReviewRating::ONE->value));
        $this->assertTrue(Review::isValidRating(ReviewRating::FIVE->value));
        $this->assertTrue(Review::isValidRating(ReviewRating::TEN->value));
        $this->assertFalse(Review::isValidRating(6));
        $this->assertFalse(Review::isValidRating(0));
        $this->assertFalse(Review::isValidRating(11));
    }
}
