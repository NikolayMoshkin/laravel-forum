<?php

namespace App\Policies;

use App\Thread;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Thread $thread)
    {
        return auth()->id() === $thread->owner->id;
    }


    public function delete(User $user, Thread $thread)
    {
        return auth()->id() === $thread->owner->id;
    }


}
