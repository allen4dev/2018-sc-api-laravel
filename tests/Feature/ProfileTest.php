<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}
