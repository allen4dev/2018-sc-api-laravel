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

        $this->json('GET', $track->path())
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

        $this->json('GET', $track->path())
            ->assertJson([ 'data' => [
                'links' => [
                    'self' => route('tracks.show', [ 'id' => $track->id ])
                ]
            ]]);
    }
}
