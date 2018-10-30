<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

class FetchTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_fetch_tracks()
    {
        $track = create(Track::class);

        $this->json('GET', $track->path())
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id'   => (string) $track->id,
                ]
            ]);
    }
}
