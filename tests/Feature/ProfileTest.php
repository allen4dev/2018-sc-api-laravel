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
    public function guests_can_fetch_all_tracks_from_a_user()
    {
        $user = create(User::class);

        $tracksByUser = create(Track::class, [ 'user_id' => $user->id ], 2);
        $trackNotByUser = create(Track::class);

        $this->json('GET', $user->path() . '/tracks')
            ->assertStatus(200)
            ->assertJson(['data' => [
                [
                    'type' => 'tracks',
                    'id'   => '1',
                ],
                [
                    'type' => 'tracks',
                    'id'   => '2',
                ],
            ]]);
    }
    
    /** @test */
    public function guests_can_fetch_the_albums_from_a_user()
    {
        $user = create(User::class);

        $albumByUser = create(Album::class, [ 'user_id' => $user->id ]);
        $albumNotByUser = create(Album::class);

        $this->json('GET', $user->path() . '/albums')
            ->assertJson(['data' => [
                [
                    'type' => 'albums',
                    'id' => $albumByUser->id,
                ]
        ]]);
    }

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
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
