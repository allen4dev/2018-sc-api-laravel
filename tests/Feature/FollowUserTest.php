<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class FollowUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_follow_other_users()
    {
        $this->signin();

        $userToFollow = create(User::class);

        $this->json('POST', $userToFollow->path() . '/follow')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'users',
                'id' => (string) $userToFollow->id,
            ]]);

        $this->assertDatabaseHas('followers', [
            'follower_id'  => auth()->id(),
            'following_id' => $userToFollow->id
        ]);
    }
}
