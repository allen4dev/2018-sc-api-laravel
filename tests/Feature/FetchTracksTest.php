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

    /** @test */
    public function guests_can_fetch_all_tracks_from_a_user()
    {
        $this->signin();

        $tracksByUser = create(Track::class, [ 'user_id' => auth()->id() ], 2);
        $trackNotByUser = create(Track::class);

        $this->json('GET', auth()->user()->path() . '/tracks')
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
}
