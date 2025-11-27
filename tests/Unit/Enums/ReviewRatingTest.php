<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ReviewRating;
use Tests\TestCase;

/**
 * ReviewRating enum unit tests.
 */
class ReviewRatingTest extends TestCase
{
    /**
     * Test review rating values.
     */
    public function test_review_rating_values(): void
    {
        $values = ReviewRating::values();

        $this->assertContains(ReviewRating::ONE->value, $values);
        $this->assertContains(ReviewRating::FIVE->value, $values);
        $this->assertContains(ReviewRating::TEN->value, $values);
    }

    /**
     * Test review rating min and max.
     */
    public function test_review_rating_min_max(): void
    {
        $this->assertEquals(1, ReviewRating::min());
        $this->assertEquals(10, ReviewRating::max());
    }
}
