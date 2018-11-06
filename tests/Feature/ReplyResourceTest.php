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

        $track = create(Track::class, [ 'published' => true ]);

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

    /** @test */
    public function it_should_contain_a_relationships_object_under_data_containing_a_user_and_track_identifiers()
    {
        $this->signin();

        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson(['data' => [
                'relationships' => [
                    'user'  => [ 'data' => [ 'type' => 'users', 'id' => (string) $reply->user_id ] ],
                    'track' => [ 'data' => [ 'type' => 'tracks', 'id' => (string) $reply->track_id ] ],
                ]
            ]]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_reply_resources_under_a_data_object()
    {
        $track = create(Track::class);

        $replyOne = create(Reply::class, [ 'track_id' => $track->id ]);
        $replyTwo = create(Reply::class, [ 'track_id' => $track->id ]);

        $this->fetchTrackReplies($track)
            ->assertJson(['data' => [
                [
                    'type' => 'replies',
                    'id'   => (string) $replyOne->id,
                ],
                [
                    'type' => 'replies',
                    'id'   => (string) $replyTwo->id,
                ],
            ]]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $track = create(Track::class);

        $this->fetchTrackReplies($track)
            ->assertJson([ 'links' => [
                'self' => route('replies.index', [ 'id' => $track->id ]),
            ]]);
    }

    public function replyTrack($track, $details)
    {
        return $this->json('POST', $track->path() . '/replies', $details);
    }

    public function fetchTrackReplies($track)
    {
        return $this->json('GET', $track->path() . '/replies');
    }
}
