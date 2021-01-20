<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Acting as a user
     * @return $user
     */
    public function signIn(?User $user = null)
    {
        $user = $user ?: factory(User::class)->create();
        $this->actingAs($user);
        return $user;
    }
}
