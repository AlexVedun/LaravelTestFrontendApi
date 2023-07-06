<?php

namespace App\Interfaces;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getUserByEmail(string $email): ?User;
    public function createUser(array $userData): ?User;
    public function updateUser(array $userData): ?User;
    public function deleteUser(): bool;
    public function getAllUsers(): Collection;
    public function getDeletedUsers(Carbon $date = null): Collection;
}
