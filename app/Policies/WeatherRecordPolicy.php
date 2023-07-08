<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeatherRecord;
use Illuminate\Auth\Access\Response;

class WeatherRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WeatherRecord $weatherRecord): bool
    {
        return $user->is_admin || $user->id == $weatherRecord->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WeatherRecord $weatherRecord): bool
    {
        return $user->is_admin || $user->id == $weatherRecord->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeatherRecord $weatherRecord): bool
    {
        return $user->is_admin || $user->id == $weatherRecord->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WeatherRecord $weatherRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WeatherRecord $weatherRecord): bool
    {
        return false;
    }
}
