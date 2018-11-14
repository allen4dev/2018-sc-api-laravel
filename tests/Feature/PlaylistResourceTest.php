<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Playlist;
use App\Track;
use App\User;

class PlaylistResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $playlist = create(Playlist::class);

        $this->fetchPlaylist($playlist)
            ->assertJson([
                'data' => [
                    'type' => 'playlists',
                    'id'   => (string) $playlist->id,
                    'attributes' => [
                        'title' => $playlist->title,
                        'photo' => $playlist->photo,
                        'created_at' => (string) $playlist->created_at,
                        'updated_at' => (string) $playlist->updated_at,
                        'time_since' => $playlist->created_at->diffForHumans(),
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_the_favorited_shared__and_tracks_count_in_his_attributes()
    {
        $playlist = create(Playlist::class);

        $tracks = create(Track::class, [ 'published' => true ], 2);

        $user = create(User::class);

        $values = $tracks->map(function ($track) use ( $playlist ) {
            return [
                'user_id'     => $playlist->user->id,
                'track_id'    => $track->id,
                'playlist_id' => $playlist->id,
            ];
        });

        Db::table('playlist_track')->insert($values->toArray());

        Db::table('favorites')->insert([
            'user_id' => $user->id,
            'type'    => 'playlist',
            'favorited_id'   => $playlist->id,
            'favorited_type' => Playlist::class,
        ]);

        Db::table('shareds')->insert([
            'user_id' => $user->id,
            'type'    => 'playlist',
            'shared_id'   => $playlist->id,
            'shared_type' => Playlist::class,
        ]);

        $this->fetchPlaylist($playlist)
            ->assertJson([
                'data' => [
                    'type' => 'playlists',
                    'id'   => (string) $playlist->id,
                    'attributes' => [
                        'favorited_count' => 1,
                        'tracks_count'    => 2,
                        'shared_count'    => 1,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $playlist = create(Playlist::class);

        $this->fetchPlaylist($playlist)
            ->assertJson(['data' => [
                'links' => [ 'self' => route('playlists.show', [ 'id' => $playlist->id ]) ]
            ]]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_under_data_containing_identifiers_for_his_related_information()
    {
        $this->signin();

        $playlist = create(Playlist::class, [ 'user_id' => auth()->id() ]);

        $this->fetchPlaylist($playlist)
            ->assertJson(['data' => [
                'relationships' => [
                    'user' => [
                        'data' => [ 'type' => 'users', 'id' => (string) auth()->id() ]
                    ]
                ]]
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_playlist_owner_if_the_request_sends_a_include_query_parameter_with_value_user()
    {
        $user  = create(User::class);
        $playlist = create(Playlist::class, [ 'user_id' => $user->id ]);

        $this->json('GET', $playlist->path() . '?include=user')
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
    public function it_should_also_contain_the_playlist_tracks_if_the_request_sends_a_include_query_parameter_with_value_tracks()
    {
        $user  = create(User::class);
        $playlist = create(Playlist::class, [ 'user_id' => $user->id ]);

        $track = create(Track::class, [ 'published' => true ]);

        Db::table('playlist_track')->insert([
            'user_id' => $user->id,
            'playlist_id' => $playlist->id,
            'track_id' => $track->id,
        ]);

        $this->json('GET', $playlist->path() . '?include=tracks')
            ->assertJson([
                'included' => [
                    [
                        'type' => 'tracks',
                        'id'   => (string) $track->id,
                        'attributes' => [
                            'title' => $track->title,
                        ]
                    ],
                ]  
            ]);
    }

    /** @test */
    public function it_should_also_contain_the_users_who_favorited_the_playlist_if_the_request_sends_a_include_query_parameter_with_value_favorites()
    {
        $user  = create(User::class);
        $playlist = create(Playlist::class);

        Db::table('favorites')->insert([
            'user_id' => $user->id,
            'type'    => 'playlist',
            'favorited_id'   => $playlist->id,
            'favorited_type' => Playlist::class,
        ]);

        $this->json('GET', $playlist->path() . '?include=favorites')
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
    public function it_should_also_contain_the_users_who_shared_the_playlist_if_the_request_sends_a_include_query_parameter_with_value_shared()
    {
        $user  = create(User::class);
        $playlist = create(Playlist::class);

        Db::table('shareds')->insert([
            'user_id' => $user->id,
            'type'    => 'playlist',
            'shared_id'   => $playlist->id,
            'shared_type' => Playlist::class,
        ]);

        $this->json('GET', $playlist->path() . '?include=shared')
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
    public function a_collection_should_contain_a_list_of_playlist_resources_under_a_data_object()
    {
        $user = create(User::class);

        create(Playlist::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/playlists')
            ->assertJson(['data' => [
                [
                    'type' => 'playlists',
                    'id' => '1',
                ],
                [
                    'type' => 'playlists',
                    'id' => '2',
                ],
            ]]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $user = create(User::class);

        create(Playlist::class, [ 'user_id' => $user->id ], 2);

        $this->json('GET', $user->path() . '/playlists')
            ->assertJson([
                'links' => [
                    'self' => route('users.playlists', [ 'id' => $user->id ]),
                ]
            ]);
    }

    public function fetchPlaylist($playlist)
    {
        return $this->json('GET', $playlist->path());
    }
}
