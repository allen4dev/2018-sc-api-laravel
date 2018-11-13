<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
use App\Playlist;
use App\Track;

class SharesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_share_a_published_track()
    {
        $this->signin();

        $track = create(Track::class, [ 'published' => true ]);

        $this->json('POST', $track->path() . '/share')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]]);

        $this->assertDatabaseHas('shareds', [
            'user_id' => auth()->id(),
            'shared_id'   => $track->id,
            'shared_type' => Track::class,
        ]);
    }

    /** @test */
    public function a_user_can_share_a_published_album()
    {
        $this->withoutExceptionHandling();
        $this->signin();

        $album = create(Album::class, [ 'published' => true ]);

        $this->json('POST', $album->path() . '/share')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);
        
            $this->assertDatabaseHas('shareds', [
                'user_id' => auth()->id(),
                'shared_id'   => $album->id,
                'shared_type' => Album::class,
            ]);
    }

    /** @test */
    public function a_user_can_share_a_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class);

        $this->json('POST', $playlist->path() . '/share')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'playlists',
                'id'   => (string) $playlist->id,
            ]]);

            $this->assertDatabaseHas('shareds', [
                'user_id' => auth()->id(),
                'shared_id'   => $playlist->id,
                'shared_type' => Playlist::class,
            ]);
    }
}
