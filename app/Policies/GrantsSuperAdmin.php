<?php


namespace App\Policies;


use App\User;

trait GrantsSuperAdmin {

    function before(User $user, $ability) {
        if ($user->admin) {
            \Log::info("Super admin bypass");
            return true;
        }
        return null;
    }
}