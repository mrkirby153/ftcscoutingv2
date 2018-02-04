<?php


namespace App\Policies;


use App\User;

trait GrantsSuperAdmin {

    function before(User $user, $ability) {
        if ($user->admin) {
            return true;
        }
        return null;
    }
}