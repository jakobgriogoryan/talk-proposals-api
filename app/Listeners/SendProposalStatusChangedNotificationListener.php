<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ProposalStatusChanged;
use App\Jobs\SendProposalStatusChangedNotificationJob;
/**
 * Listener for ProposalStatusChanged event.
 * Dispatches notification job to send email to speaker.
 */
class SendProposalStatusChangedNotificationListener
{

    /**
     * Handle the event.
     */
    public function handle(ProposalStatusChanged $event): void
    {
        SendProposalStatusChangedNotificationJob::dispatch(
            $event->proposal,
            $event->oldStatus,
            $event->newStatus
        );
    }
}

