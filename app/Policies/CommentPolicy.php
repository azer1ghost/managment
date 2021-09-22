<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Inquiry;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $comment->getAttribute('user_id') === $user->getAttribute('id');

    }

    public function delete(User $user, Comment $comment): bool
    {
        return $comment->getAttribute('user_id') === $user->getAttribute('id');
    }
}
