<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $event->status !== 'draft' || $user->id === $event->organizer_id || $user->isAdmin();
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOrganizer() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id || $user->isAdmin();
    }
}
