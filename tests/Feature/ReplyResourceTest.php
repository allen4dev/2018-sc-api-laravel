<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\Reply;

class ReplyResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $this->signin();

        $track = create(Track::class);

        $details = raw(Reply::class);

        $this->replyTrack($track, $details)
            ->assertJson(['data' => [
                'type' => 'replies',
                'id'   => '1',
                'attributes' => [
                    'body' => $details['body'],
                ]
            ]]);
    }

    public function replyTrack($track, $details)
    {
        return $this->json('POST', $track->path() . '/replies', $details);
    }
}
