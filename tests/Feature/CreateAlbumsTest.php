<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
use App\Track;

class CreateAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_albums()
    {
        $this->json('POST', '/api/albums', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_create_albums()
    {
        $this->signin();

        $album = raw(Album::class);

        $tracks = create(Track::class, [ 'user_id' => auth()->id() ], 2);

        $this->json('POST', '/api/albums', [ 'details' => $album, 'tracks' => $tracks->pluck('id') ])
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => '1',
            ]]);

        $this->assertDatabaseHas('albums', [
            'id'    => 1,
            'title' => $album['title'],
        ]);
    }

    /** @test */
    public function after_create_an_album_the_sended_tracks_should_be_related_to_the_album()
    {
        $this->signin();

        $album = raw(Album::class);

        $tracks = create(Track::class, [ 'user_id' => auth()->id() ], 2);

        $this->json('POST', '/api/albums', [ 'details' => $album, 'tracks' => $tracks->pluck('id') ]);

        $tracks->map(function ($track) {
            $this->assertDatabaseHas('tracks', [
                'id'       => $track->id,
                'user_id'  => auth()->id(),
                'album_id' => 1,
            ]);
        });
    }
}
