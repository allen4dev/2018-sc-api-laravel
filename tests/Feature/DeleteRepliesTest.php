<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
use App\Track;

class DeleteRepliesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function after_delete_a_track_his_replies_should_also_be_deleted()
    {
        $this->signin();
        
        $track = create(Track::class, [ 'user_id' => auth()->id() ]);
        $reply = create(Reply::class, [ 'track_id' => $track->id ]);

        $this->json('DELETE', $track->path());
        
        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
            'track_id' => $track->id,
        ]);
    }
}
