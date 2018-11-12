<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
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
    }

    /** @test */
    public function a_user_can_share_a_published_album()
    {
        $this->signin();

        $album = create(Album::class, [ 'published' => true ]);

        $this->json('POST', $album->path() . '/share')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]]);
    }
}
