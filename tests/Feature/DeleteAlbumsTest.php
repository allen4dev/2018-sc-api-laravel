<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
use App\Track;

class DeleteAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_albums()
    {
        $this->json('DELETE', '/api/albums/1')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_delete_his_albums()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', $album->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('albums', [
            'id' => $album->id,
            'user_id' => auth()->id(),
        ]);
    }

    /** @test */
    public function a_user_cannot_delete_albums_from_other_uses()
    {
        $this->signin();

        $album = create(Album::class);

        $this->json('DELETE', $album->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'user_id' => $album->user_id,
        ]);
    }

    /** @test */
    public function after_delete_an_album_the_related_tracks_should_not_be_related_to_him_anymore()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $tracks = create(Track::class, [
            'user_id'   => auth()->id(),
            'album_id'  => $album->id,
            'published' => true,
        ], 2);

        $this->json('DELETE', $album->path());

        $tracks->fresh()->each(function ($track) {
            $this->assertNull($track->album_id);
        });
    }
}
