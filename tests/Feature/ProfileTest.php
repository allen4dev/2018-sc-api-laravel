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
    public function a_user_can_fetch_his_profile_information()
    {
        $this->signin();

        $this->json('GET', '/api/me')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'users',
                'id' => (string) auth()->id(),
            ]]);
    }

    /** @test */
    public function a_user_can_fetch_his_published_and_unpublished_tracks()
    {
        $this->signin();

        $published   = create(Track::class, [ 'user_id' => auth()->id(), 'published' => true ]);
        $unpublished = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', '/api/me/tracks')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'tracks',
                    'id'   => (string) $published->id,
                ],
                [
                    'type' => 'tracks',
                    'id'   => (string) $unpublished->id,
                ],
            ]]);
    }

    /** @test */
    public function a_user_can_fetch_his_published_and_unpublished_albums()
    {
        $this->signin();

        $published   = create(Album::class, [ 'user_id' => auth()->id(), 'published' => true ]);
        $unpublished = create(Album::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', '/api/me/albums')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'albums',
                    'id'   => (string) $published->id,
                ],
                [
                    'type' => 'albums',
                    'id'   => (string) $unpublished->id,
                ],
            ]]);
    }

    /** @test */
    public function a_user_can_fetch_his_playlists()
    {
        $this->signin();

        $playlists = create(Playlist::class, [ 'user_id' => auth()->id() ], 2);

        $this->json('GET', '/api/me/playlists')
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
    public function a_user_can_fetch_all_his_followers()
    {
        $this->signin();

        $follower = create(User::class);

        Db::table('followers')->insert([
            'follower_id'  => $follower->id,
            'following_id' => auth()->id(),
        ]);

        $this->json('GET', '/api/me/followers')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'users',
                    'id'   => (string) $follower->id,
                ],
            ]]);
    }

    /** @test */
    public function a_user_can_fetch_all_users_who_he_is_following()
    {
        $this->signin();

        $user = create(User::class);

        Db::table('followers')->insert([
            'follower_id'  => auth()->id(),
            'following_id' => $user->id,
        ]);

        $this->json('GET', '/api/me/followings')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'users',
                    'id'   => (string) $user->id,
                ],
            ]]);
    }

    /** @test */
    public function a_user_can_fetch_his_unread_notifications()
    {
        $this->signin();

        $user = create(User::class);

        $this->json('POST', $user->path() . '/follow');

        auth()->logout();

        $this->signin($user);

        $notification = $user->unreadNotifications()->first();

        $this->json('GET', '/api/me/notifications')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'notifications',
                    'id'   => (string) $notification->id,
                ]
            ]]);
    }
}
