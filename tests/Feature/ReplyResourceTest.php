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

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson(['data' => [
                'links' => [ 'self' => route('replies.show', [ 'id' => $reply->id ]) ]
            ]]);
    }

    public function replyTrack($track, $details)
    {
        return $this->json('POST', $track->path() . '/replies', $details);
    }
}
