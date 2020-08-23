<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        // Password can still be set in tests, so we look first if a password is set, else make a random one
        if (empty($user->password)) {
            $user->password = Hash::make(Str::random(50));
        }
    }
}
