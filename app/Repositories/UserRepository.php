<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    public function getUserByEmail(string $email): ?User
    {
        return User::whereEmail($email)->first();
    }

    public function createUser(array $userData): ?User
    {
        try {
            return User::create($userData);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }

        return null;
    }

    public function updateUser(array $userData): ?User
    {
        try {
            $user = Auth::user();
            $user->update($userData);
            return $user->refresh();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }

        return null;
    }

    public function deleteUser(): bool
    {
        try {
            $user = Auth::user();
            $user->delete();
            return true;
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::debug($exception->getTraceAsString());
        }

        return false;
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function getDeletedUsers(Carbon $date = null): Collection
    {
        return User::whereNotNull('deleted_at')
            ->when($date, function (Builder $query) use ($date) {
                return $query->where('deleted_at', '<=', $date);
            })
            ->get();
    }
}
