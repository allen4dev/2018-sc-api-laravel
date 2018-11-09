<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Reply;
use App\Track;
use App\User;

class TrackResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $track = create(Track::class, [ 'published' => true ]);

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
        $track = create(Track::class, [ 'published' => true ]);

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
    // public function a_track_identifier_should_contain_a_data_with_a_type_and_the_id_of_the_track()
    // {
    //     $reply = create(Reply::class);

    //     $this->json('GET', $reply->path())
    //         ->assertJson([
    //             'data' => [
    //                 'relationships' => [
    //                     'track' => [
    //                         'data' => [ 'type' => 'tracks', 'id' => (string) $reply->track_id ]
    //                     ]
    //                 ]
    //             ]
    //         ]);
    // }

    /** @test */
    // public function a_track_identifier_should_contain_a_links_object_containing_a_url_to_the_track_path()
    // {
    //     $reply = create(Reply::class);

    //     $this->json('GET', $reply->path())
    //         ->assertJson([
    //             'data' => [
    //                 'relationships' => [
    //                     'track' => [
    //                         'links' => [ 'self' => route('tracks.show', [ 'id' => $reply->id ]) ],
    //                     ]
    //                 ]
    //             ]
    //         ]);
    // }

    /** @test */
    public function a_collection_should_contain_a_list_of_track_resources_under_a_data_object()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);

        $trackOne = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);
        $trackTwo = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);

        $this->json('GET', $user->path() . '/tracks')
            ->assertJson(['data' => [
                [
                    'type' => 'tracks',
                    'id'   => (string) $trackOne->id,
                    'attributes' => [
                        'title' => $trackOne->title,
                        // more fields
                    ],
                ],
                [
                    'type' => 'tracks',
                    'id'   => (string) $trackTwo->id,
                    'attributes' => [
                        'title' => $trackTwo->title,
                        // more fields
                    ],
                ],
            ]]);
    }

    /** @test */
    // public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    // {
    //     $user = create(User::class);

    //     $tracks = create(Track::class, [ 'user_id' => $user->id ], 2);

    //     $this->json('GET', $user->path() . '/tracks')
    //         ->assertJson([
    //             'links' => [
    //                 'self' => route('users.tracks', [ 'user_id' => $user->id ]),
    //             ],
    //         ]);
    // }

    public function fetchTrack($track)
    {
        return $this->json('GET', $track->path());
    }
}
