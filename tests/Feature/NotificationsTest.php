<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_is_notified_after_other_users_follow_him()
    {
        $this->signin();

        $user = create(User::class);

        $this->followUser($user);

        $this->assertCount(1, $user->unreadNotifications);
    }

    public function followUser($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }
}
