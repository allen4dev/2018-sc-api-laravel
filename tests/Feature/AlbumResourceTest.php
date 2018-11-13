<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\User;

class AlbumResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $album = create(Album::class, [ 'published' => true ]);

        $this->fetchAlbum($album)
            ->assertJson([
                'data' => [
                    'type' => 'albums',
                    'id'   => (string) $album->id,
                    'attributes' => [
                        'title' => $album->title,
                        'published' => $album->published,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $album = create(Album::class, [ 'published' => true ]);

        $this->fetchAlbum($album)
            ->assertJson(['data' => [
                'links' => [ 'self' => route('albums.show', ['id' => $album->id]) ]
            ]]);
    }

    /** @test */
    public function it_should_contain_a_relationships_object_under_data_containing_identifiers_for_his_related_information()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);
        
        $this->fetchAlbum($album)
            ->assertJson(['data' => [
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'users',
                            'id'   => (string) auth()->id(),
                        ]
                    ]
                ]
            ]]);
    }

    /** @test */
    public function it_should_also_contain_the_users_who_favorited_the_album_if_the_request_sends_a_include_query_parameter_with_value_favorites()
    {
        $user  = create(User::class);
        $album = create(Album::class, [ 'published' => true ]);

        Db::table('favorites')->insert([
            'user_id' => $user->id,
            'type'    => 'album',
            'favorited_id'   => $album->id,
            'favorited_type' => Album::class,
        ]);

        $this->json('GET', $album->path() . '?include=favorites')
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
    public function it_should_also_contain_the_album_owner_if_the_request_sends_a_include_query_parameter_with_value_user()
    {
        $user  = create(User::class);
        $album = create(Album::class, [ 'user_id' => $user->id, 'published' => true ]);

        $this->json('GET', $album->path() . '?include=user')
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
    public function a_collection_should_contain_a_list_of_album_resources_under_a_data_object()
    {
        $user = create(User::class);

        create(Album::class, [ 'user_id' => $user->id, 'published' => true ], 2);

        $this->json('GET', $user->path() . '/albums')
            ->assertJson(['data' => [
                [
                    'type' => 'albums',
                    'id'   => '1',
                ],
                [
                    'type' => 'albums',
                    'id'   => '2',
                ],
            ]]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $user = create(User::class);

        $this->json('GET', $user->path() . '/albums')
            ->assertJson([
                'links' => [ 'self' => route('users.albums', [ 'id' => $user->id ]) ],
            ]);
    }

    public function fetchAlbum($album)
    {
        return $this->json('GET', $album->path());
    }
}
