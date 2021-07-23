<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquiryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Inquiry $inquiry)
    {
        //
    }

//    public function create(User $user)
//    {
//        //
//    }
//
//    public function update(User $user, Inquiry $inquiry)
//    {
//        //
//    }
//
//    public function delete(User $user, Inquiry $inquiry)
//    {
//        //
//    }
//
//    public function restore(User $user, Inquiry $inquiry)
//    {
//        //
//    }
//
//    public function forceDelete(User $user, Inquiry $inquiry)
//    {
//        //
//    }
}
