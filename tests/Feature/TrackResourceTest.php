<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;

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
    public function it_should_contain_a_relationships_object_under_data_containing_a_user_identifier_rosource()
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

    public function fetchTrack($track)
    {
        return $this->json('GET', $track->path());
    }
}
