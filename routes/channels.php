<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('proposals', function ($user) {
    // All authenticated users can listen to proposals channel
    return $user !== null;
});

Broadcast::channel('proposal.{proposalId}', function ($user, $proposalId) {
    // Users can listen to specific proposal if they can view it
    $proposal = \App\Models\Proposal::find($proposalId);
    if (!$proposal) {
        return false;
    }
    
    // Admin, reviewer, or the proposal owner can listen
    return $user->isAdmin() || $user->isReviewer() || $proposal->user_id === $user->id;
});

Broadcast::channel('proposals.{proposalId}', function ($user, $proposalId) {
    // Users can listen to specific proposal if they can view it
    $proposal = \App\Models\Proposal::find($proposalId);
    if (!$proposal) {
        return false;
    }
    
    // Admin, reviewer, or the proposal owner can listen
    return $user->isAdmin() || $user->isReviewer() || $proposal->user_id === $user->id;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    // Users can only listen to their own user channel
    return (int) $user->id === (int) $userId;
});

