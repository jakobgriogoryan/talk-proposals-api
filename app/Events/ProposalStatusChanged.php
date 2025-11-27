<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProposalStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Proposal $proposal,
        public string $oldStatus,
        public string $newStatus
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('proposals'), // Global channel for list updates
            new PrivateChannel('proposals.'.$this->proposal->id), // Specific proposal channel
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ProposalStatusChanged';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // Ensure relationships are loaded for the resource
        if (!$this->proposal->relationLoaded('user')) {
            $this->proposal->load('user');
        }
        if (!$this->proposal->relationLoaded('tags')) {
            $this->proposal->load('tags');
        }

        return [
            'proposal_id' => $this->proposal->id,
            'new_status' => $this->newStatus,
            'old_status' => $this->oldStatus,
            'proposal' => new ProposalResource($this->proposal),
            'message' => "Proposal '{$this->proposal->title}' status changed from {$this->oldStatus} to {$this->newStatus}",
        ];
    }
}
