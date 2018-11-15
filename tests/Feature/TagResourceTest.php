<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\Playlist;
use App\Tag;
use App\Track;

class TagResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $tag = create(Tag::class);

        $this->json('GET', $tag->path())
            ->assertJson([
                'data' => [
                    'type' => 'tags',
                    'id'   => (string) $tag->id,
                    'attributes' => [
                        'name'      => $tag->name,
                        'created_at' => (string) $tag->created_at,
                        'updated_at' => (string) $tag->updated_at,
                        'time_since' => $tag->created_at->diffForHumans(),
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_the_albums_playlists_and_tracks_count_in_his_attributes()
    {
        $tag = create(Tag::class);

        $track = create(Track::class, [ 'published' => true ]);

        $albums = create(Album::class, [ 'published' => true ], 2);
        
        $playlist = create(Playlist::class);

        $resources = collect([ $track, $albums, $playlist ])->flatten();


        $resources->each(function ($resource) use ( $tag ) {
            Db::table('taggables')->insert([
                'tag_id' => $tag->id,
                'taggable_id'   => $resource->id,
                'taggable_type' => get_class($resource),
            ]);
        });

        $this->json('GET', $tag->path())
            ->assertJson([
                'data' => [
                    'type' => 'tags',
                    'id'   => (string) $tag->id,
                    'attributes' => [
                        'albums_count'    => 2,
                        'playlists_count' => 1,
                        'tracks_count'    => 1,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $tag = create(Tag::class);

        $this->json('GET', $tag->path())
            ->assertJson([ 'data' => [
                'links' => [
                    'self' => route('tags.show', $tag)
                ]
            ]]);
    }

}
