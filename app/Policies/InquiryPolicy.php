<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquiryPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user)
    {
        return $user->role->hasPermission('viewAny-inquiry');
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return
            $user->role->hasPermission('view-inquiry') &&
            $inquiry->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission('create-inquiry');
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return
            $user->role->hasPermission('update-inquiry') &&
            $inquiry->user_id === $user->id &&
            $inquiry->created_at->addMinutes(7) > now();
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return
            $user->role->hasPermission('delete-inquiry') &&
            $inquiry->user_id === $user->id &&
            $inquiry->created_at->addMinutes(7) > now();
    }

    public function restore(User $user, Inquiry $inquiry): bool
    {
        return
            $user->role->hasPermission('restore-inquiry') &&
            $inquiry->user_id === $user->id &&
            $inquiry->created_at->addMinutes(7) > now();
    }

    public function forceDelete(User $user, Inquiry $inquiry): bool
    {
        return
            $user->role->hasPermission('forceDelete-inquiry') &&
            $inquiry->user_id === $user->id &&
            $inquiry->created_at->addMinutes(7) > now();
    }
}
