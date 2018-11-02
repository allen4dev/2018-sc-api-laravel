<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
use App\Track;

class DeleteTracksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_delete_his_tracks()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', $track->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
            'title' => $track->title,
            'user_id' => auth()->id(),
        ]);
    }
}
