<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin()
            || $user->id === $order->user_id
            || $user->id === optional($order->crop)->user_id;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->id === optional($order->crop)->user_id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->id === $order->user_id;
    }
}
