<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\User;

class FollowUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_follow_other_users()
    {
        $this->signin();

        $userToFollow = create(User::class);

        $this->follow($userToFollow)
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

    /** @test */
    public function a_user_cannot_follow_more_than_once_the_same_user()
    {
        $this->signin();

        $userToFollow = create(User::class);

        try {
            $this->follow($userToFollow);
            $this->follow($userToFollow);
        } catch (Exception $e) {
            $this->fail('Did not expect to follow the same user more than once.');
        }

        $this->assertCount(1, $userToFollow->followers);
    }

    /** @test */
    public function a_user_can_unfollow_a_currently_followed_user()
    {
        $this->signin();

        $userToUnfollow = create(User::class);
        
        Db::table('followers')->insert([
            'follower_id'  => auth()->id(),
            'following_id' => $userToUnfollow->id,
        ]);

        $this->json('DELETE', $userToUnfollow->path() . '/unfollow')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'users',
                'id'   => (string) $userToUnfollow->id,
            ]]);


        $this->assertDatabaseMissing('followers' ,[
            'follower_id'  => auth()->id(),
            'following_id' => $userToUnfollow->id,
        ]);
    }

    public function follow($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }

}
