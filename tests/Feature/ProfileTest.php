<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\Playlist;
use App\Track;
use App\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_all_playlists_from_a_user()
    {
        $user = create(User::class);

        $playlistsByUser = create(Playlist::class, [ 'user_id' => $user->id ], 2);
        $playlistNotByUser = create(Playlist::class);

        $this->json('GET', $user->path() . '/playlists')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'playlists',
                    'id'   => '1',
                ],
                [
                    'type' => 'playlists',
                    'id'   => '2',
                ],
            ]]);
    }

    /** @test */
    public function guests_can_fetch_all_users_who_a_user_is_following()
    {
        $user = create(User::class);

        $followedUsers = create(User::class, [], 2);
        $notFollowedUser = create(User::class);

        $values = $followedUsers->map(function ($followed) use ( $user ) {
            return [ 'follower_id' => $user->id, 'following_id' => $followed->id ];
        });

        Db::table('followers')->insert($values->toArray());

        $this->json('GET', $user->path() . '/following')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'users',
                    'id'   => '2',
                ],
                [
                    'type' => 'users',
                    'id'   => '3',
                ],
            ]]);
    }

    /** @test */
    public function guests_can_fetch_all_users_who_are_following_a_user()
    {
        $user = create(User::class);
        
        $usersFollowing = create(User::class, [], 2);

        $values = $usersFollowing->map(function ($following) use ( $user ) {
            return [ 'follower_id' => $following->id, 'following_id' => $user->id ];
        });

        Db::table('followers')->insert($values->toArray());

        $this->json('GET', $user->path() . '/followers')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'users',
                    'id'   => '2',
                ],
                [
                    'type' => 'users',
                    'id'   => '3',
                ],
            ]]);
    }
}
