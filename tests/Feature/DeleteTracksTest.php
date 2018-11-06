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
    public function guests_cannot_delete_tracks()
    {
        $this->json('DELETE', '/api/tracks/1')
            ->assertStatus(401);
    }

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

    /** @test */
    public function only_authorized_users_can_delete_his_tracks()
    {
        $this->signin();
        
        $track = create(Track::class);

        $this->json('DELETE', $track->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('tracks', [
            'id' => $track->id,
            'user_id' => $track->user_id,
            'title' => $track->title,
        ]);
    }

    /** @test */
    public function after_delete_a_user_his_tracks_should_also_be_deleted()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', auth()->user()->path());

        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
            'user_id' => auth()->id(),
        ]);
    }
}
