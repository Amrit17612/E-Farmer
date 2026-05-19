<?php

namespace App\Policies;

use App\Models\Crop;
use App\Models\User;

class CropPolicy
{
    public function view(User $user, Crop $crop): bool
    {
        return $user->isAdmin() || $user->id === $crop->user_id;
    }

    public function update(User $user, Crop $crop): bool
    {
        return $user->isAdmin() || $user->id === $crop->user_id;
    }

    public function delete(User $user, Crop $crop): bool
    {
        return $user->isAdmin() || $user->id === $crop->user_id;
    }
}
