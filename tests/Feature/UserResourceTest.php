<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\Playlist;
use App\Track;
use App\User;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $user = create(User::class);

        $this->json('GET', $user->path())
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'id'   => (string) $user->id,
                    'attributes' => [
                        'username' => $user->username,
                        'email'    => $user->email,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_published_tracks_created_by_the_user_if_the_request_sends_a_include_query_parameter_with_value_albums()
    {
        $user  = create(User::class);

        $publishedTrack = create(Track::class, [ 'user_id' => $user->id, 'published' => true ]);
        $unpublishedTrack = create(Track::class, [ 'user_id' => $user->id ]);

        $response = $this->json('GET', $user->path() . '?include=tracks');
            
        $response->assertJson([
            'included' => [
                [
                    'type' => 'tracks',
                    'id'   => (string) $publishedTrack->id,
                    'attributes' => [
                        'title' => $publishedTrack->title,
                    ]
                ],
            ]  
        ]);

        $this->assertEquals(1, ($response->original)->count());
    }

    /** @test */
    public function it_should_also_contain_the_published_albums_created_by_the_user_if_the_request_sends_a_include_query_parameter_with_value_albums()
    {
        $user = create(User::class);

        $publishedAlbum = create(Album::class, [ 'user_id' => $user->id, 'published' => true ]);
        $unpublishedAlbum = create(Album::class, [ 'user_id' => $user->id ]);

        $response = $this->json('GET', $user->path() . '?include=albums');
            
        $response->assertJson([
            'included' => [
                [
                    'type' => 'albums',
                    'id'   => (string) $publishedAlbum->id,
                    'attributes' => [
                        'title' => $publishedAlbum->title,
                    ]
                ],
            ]  
        ]);

        $this->assertEquals(1, ($response->original)->count());
    }

    /** @test */
    public function it_should_also_contain_the_playlists_created_by_the_user_if_the_request_sends_a_include_query_parameter_with_value_playlists()
    {
        $user = create(User::class);

        $playlist = create(Playlist::class, [ 'user_id' => $user->id ]);

        $response = $this->json('GET', $user->path() . '?include=playlists');
            
        $response->assertJson([
            'included' => [
                [
                    'type' => 'playlists',
                    'id'   => (string) $playlist->id,
                    'attributes' => [
                        'title' => $playlist->title,
                    ]
                ],
            ]  
        ]);

        $this->assertEquals(1, ($response->original)->count());
    }

    /** @test */
    public function it_should_also_contain_the_user_followers_if_the_request_sends_a_include_query_parameter_with_value_followers()
    {
        $user = create(User::class);
        $follower = create(User::class);
        
        Db::table('followers')->insert([
            'follower_id'  => $follower->id,
            'following_id' => $user->id,
        ]);

        $response = $this->json('GET', $user->path() . '?include=followers');
            
        $response->assertJson([
            'included' => [
                [
                    'type' => 'users',
                    'id'   => (string) $follower->id,
                    'attributes' => [
                        'username' => $follower->username,
                        'email' => $follower->email,
                    ]
                ],
            ]  
        ]);
    }

    /** @test */
    public function it_should_also_contain_the_users_followed_by_our_user_if_the_request_sends_a_include_query_parameter_with_value_followings()
    {
        $user = create(User::class);
        $followed = create(User::class);
        
        Db::table('followers')->insert([
            'follower_id'  => $user->id,
            'following_id' => $followed->id,
        ]);

        $response = $this->json('GET', $user->path() . '?include=followings');
            
        $response->assertJson([
            'included' => [
                [
                    'type' => 'users',
                    'id'   => (string) $followed->id,
                    'attributes' => [
                        'username' => $followed->username,
                        'email' => $followed->email,
                    ]
                ],
            ]  
        ]);
    }

    /** @test */
    public function a_user_identifier_should_contain_a_data_with_a_type_and_the_id_of_the_user()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', $track->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'user' => [
                            'data' => [ 'type' => 'users', 'id' => (string) auth()->id() ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_user_identifier_should_contain_a_links_object_containing_a_url_to_the_user_path()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', $track->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'user' => [
                            'links' => [
                                'self' => route('users.show', [ 'id' => auth()->id() ])
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_user_resources_under_a_data_object()
    {

        $user = create(User::class);

        $followedUser = create(User::class);

        Db::table('followers')->insert([
            'follower_id'  => $user->id,
            'following_id' => $followedUser->id,
        ]);

        $this->json('GET', $user->path() . '/following')
            ->assertJson(['data' => [
                [
                    'type' => 'users',
                    'id'   => (string) $followedUser->id,
                ],
            ]]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $user = create(User::class);

        $this->json('GET', $user->path() . '/following')
            ->assertJson([
                'links' => [
                    'self' => route('users.following', [ 'id' => $user->id ]),
                ],
            ]);
    }
}
