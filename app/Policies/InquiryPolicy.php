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
        if ($user->isDeveloper() || $user->isAdministrator()){
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
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
            $inquiry->created_at > now()->subMinutes('60');
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return
            $user->role->hasPermission('delete-inquiry') &&
            $inquiry->user_id === $user->id &&
            $inquiry->created_at > now()->subMinutes('7');
    }

    public function restore(User $user, Inquiry $inquiry)
    {
        return
            $user->role->hasPermission('delete-inquiry') &&
            $inquiry->user_id === $user->id &&
            $inquiry->created_at > now()->subMinutes('7');
    }

//    public function forceDelete(User $user, Inquiry $inquiry)
//    {
//        //
//    }
}
