<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }
}
