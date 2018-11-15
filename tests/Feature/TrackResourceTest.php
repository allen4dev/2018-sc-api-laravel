<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Reply;
use App\Tag;
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
                        'title'      => $track->title,
                        'photo'      => $track->photo,
                        'published'  => $track->published,
                        'created_at' => (string) $track->created_at,
                        'updated_at' => (string) $track->updated_at,
                        'time_since' => $track->created_at->diffForHumans(),
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_the_favorited_replies_reproduced_and_shared_count_in_his_attributes()
    {
        $track = create(Track::class, [ 'published' => true, 'reproduced_count' => 5 ]);

        $replies = create(Reply::class, [ 'replyable_id' => $track->id ], 2);
        
        $user = $replies->first()->user;

        Db::table('favorites')->insert([
            'user_id' => $user->id,
            'type'    => 'track',
            'favorited_id'   => $track->id,
            'favorited_type' => Track::class,
        ]);

        Db::table('shareds')->insert([
            'user_id' => $user->id,
            'type'    => 'track',
            'shared_id'   => $track->id,
            'shared_type' => Track::class,
        ]);

        $this->fetchTrack($track)
            ->assertJson([
                'data' => [
                    'type' => 'tracks',
                    'id'   => (string) $track->id,
                    'attributes' => [
                        'favorited_count'  => 1,
                        'replies_count'    => 2,
                        'reproduced_count' => 5,
                        'shared_count'     => 1,
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

        $tag = create(Tag::class);

        Db::table('taggables')->insert([
            'tag_id' => $tag->id,
            'taggable_id'  => $track->id,
            'taggable_type' => Track::class,
        ]);

        $this->fetchTrack($track)
            ->assertJson(['data' => [
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'users',
                            'id' => (string) auth()->id()
                        ]
                    ],
                    'tags' => [
                        [
                            'data' => [
                                'type' => 'tags',
                                'id'   => (string) $tag->id,
                            ]
                        ]
                    ]
                ]
            ]]);
    }

    /** @test */
    public function it_should_also_contain_the_author_if_the_request_sends_a_include_query_parameter_with_value_user()
    {
        $user  = create(User::class);
        $track = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);

        $this->json('GET', $track->path() . '?include=user')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $user->id,
                        'attributes' => [
                            'username' => $user->username,
                            'email' => $user->email,
                        ]
                    ],
                ]  
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_tags_if_the_request_sends_a_include_query_parameter_with_value_tags()
    {
        $tag   = create(Tag::class);
        $track = create(Track::class, [ 'published' => true ]);

        Db::table('taggables')->insert([
            'tag_id' => $tag->id,
            'taggable_id'   => $track->id,
            'taggable_type' => Track::class,
        ]);

        $this->json('GET', $track->path() . '?include=tags')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'tags',
                        'id'   => (string) $tag->id,
                        'attributes' => [
                            'name' => $tag->name,
                        ]
                    ],
                ]  
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_users_who_favorited_the_track_if_the_request_sends_a_include_query_parameter_with_value_favorites()
    {
        $user  = create(User::class);
        $track = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);

        $user2 = create(User::class);

        Db::table('favorites')->insert([
            'user_id' => $user2->id,
            'type' => 'track',
            'favorited_id' => $track->id,
            'favorited_type' => Track::class,
        ]);

        $this->json('GET', $track->path() . '?include=favorites')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $user2->id,
                        'attributes' => [
                            'username' => $user2->username,
                            'email' => $user2->email,
                        ]
                    ],
                ]  
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_users_who_shared_the_track_if_the_request_sends_a_include_query_parameter_with_value_shared()
    {
        $user  = create(User::class);
        $track = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);

        $user2 = create(User::class);

        Db::table('shareds')->insert([
            'user_id' => $user2->id,
            'type' => 'track',
            'shared_id' => $track->id,
            'shared_type' => Track::class,
        ]);

        $this->json('GET', $track->path() . '?include=shared')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'users',
                        'id'   => (string) $user2->id,
                        'attributes' => [
                            'username' => $user2->username,
                            'email' => $user2->email,
                        ]
                    ],
                ]  
            ]);
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
                            'data' => [ 'type' => 'tracks', 'id' => (string) $reply->replyable_id ]
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

    /** @test */
    public function a_collection_should_contain_a_list_of_track_resources_under_a_data_object()
    {
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
                    ],
                ],
                [
                    'type' => 'tracks',
                    'id'   => (string) $trackTwo->id,
                    'attributes' => [
                        'title' => $trackTwo->title,
                    ],
                ],
            ]]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $user = create(User::class);

        $tracks = create(Track::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/tracks')
            ->assertJson([
                'links' => [
                    'self' => route('users.tracks', [ 'user_id' => $user->id ]),
                ],
            ]);
    }

    public function fetchTrack($track)
    {
        return $this->json('GET', $track->path());
    }
}
