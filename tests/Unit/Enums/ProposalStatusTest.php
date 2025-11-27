<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ProposalStatus;
use Tests\TestCase;

/**
 * ProposalStatus enum unit tests.
 */
class ProposalStatusTest extends TestCase
{
    /**
     * Test proposal status values.
     */
    public function test_proposal_status_values(): void
    {
        $values = ProposalStatus::values();

        $this->assertContains(ProposalStatus::PENDING->value, $values);
        $this->assertContains(ProposalStatus::APPROVED->value, $values);
        $this->assertContains(ProposalStatus::REJECTED->value, $values);
    }
}
