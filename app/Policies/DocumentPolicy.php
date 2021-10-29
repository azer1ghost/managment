<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Document $document): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $document->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Document $document): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $document->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Document $document): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $document->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Document $document): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}