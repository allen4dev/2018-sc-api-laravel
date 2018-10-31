<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

class PublishTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_users_can_publish_his_tracks()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('PATCH', $track->path() . '/publish')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
                'attributes' => [
                    'published' => true,
                ]
            ]]);

        // the tracks table should not contain anymore a record with the previous id unpublished
        // and instead should contain  a record with the previous id published
        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
            'published' => false,
        ]);

        $this->assertDatabaseHas('tracks', [
            'id' => $track->id,
            'published' => true,
        ]);
    }
}
