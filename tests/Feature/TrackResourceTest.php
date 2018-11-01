<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\Reply;

class TrackResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $track = create(Track::class);

        $this->fetchTrack($track)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id'   => (string) $track->id,
                    'attributes' => [
                        'title' => $track->title,
                        'published' => $track->published,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $track = create(Track::class);

        $this->fetchTrack($track)
            ->assertJson([ 'data' => [
                'links' => [
                    'self' => route('tracks.show', [ 'id' => $track->id ])
                ]
            ]]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_under_data_containing_identifiers_for_his_related_information()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->fetchTrack($track)
            ->assertJson(['data' => [
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'users',
                            'id' => (string) auth()->id()
                        ]
                    ]
                ]
            ]]);
    }

    /** @test */
    public function a_track_identifier_should_contain_a_data_with_a_type_and_the_id_of_the_track()
    {
        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'track' => [
                            'data' => [ 'type' => 'tracks', 'id' => (string) $reply->track_id ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_track_identifier_should_contain_a_links_object_containing_a_url_to_the_track_path()
    {
        $reply = create(Reply::class);

        $this->json('GET', $reply->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'track' => [
                            'links' => [ 'self' => route('tracks.show', [ 'id' => $reply->id ]) ],
                        ]
                    ]
                ]
            ]);
    }

    public function fetchTrack($track)
    {
        return $this->json('GET', $track->path());
    }
}
