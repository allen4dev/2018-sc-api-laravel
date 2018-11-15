<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\Tag;
use App\Track;

class CreateAlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_albums()
    {
        $this->json('POST', '/api/albums', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_create_albums()
    {
        $this->signin();

        $photo = UploadedFile::fake()->image('photo.jpg');
        
        $tag   = create(Tag::class);
        
        $tags[] = $tag->id;

        $values = [
            'details' => [
                'title' => 'My new Album',
            ],
            'photo' => $photo,
            'tags'  => $tags,
        ];

        $tracks = create(Track::class, [ 'user_id' => auth()->id() ], 2);

        $this->createAlbum($values, $tracks)
            ->assertStatus(201)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => '1',
            ]]);

        $this->assertDatabaseHas('albums', [
            'id'    => 1,
            'title' => $values['details']['title'],
        ]);

        $this->assertDatabaseHas('taggables', [
            'tag_id' => $tag->id,
            'taggable_id'   => 1,
            'taggable_type' => Album::class,
        ]);
    }

    /** @test */
    public function a_user_can_only_add_his_tracks_to_a_playlist()
    {
        $this->signin();

        $photo = UploadedFile::fake()->image('photo.jpg');

        $tag   = create(Tag::class);
        
        $tags[] = $tag->id;

        $values = [
            'details' => [
                'title' => 'My new Album',
            ],
            'photo' => $photo,
            'tags'  => $tags,
        ];

        $otherUserTrack = create(Track::class, [ 'published' => true ]);

        $response = $this->createAlbum($values, $otherUserTrack);

        $this->assertCount(0, $response->original->tracks);
    }

    /** @test */
    public function a_user_cannot_add_the_same_track_to_multiple_albums()
    {        
        $this->signin();
        
        $photo = UploadedFile::fake()->image('photo.jpg');

        $album = create(Album::class, [ 'user_id' => auth()->id(), 'photo' => $photo ]);

        $track = create(Track::class, [
            'album_id' => $album->id,
            'user_id'  => auth()->id(),
            'published' => true,
        ]);

        $tag   = create(Tag::class);
        
        $tags[] = $tag->id;

        $values = [
            'details' => [
                'title' => 'My new Album',
            ],
            'photo' => $photo,
            'tags'  => $tags,
        ];

        $response = $this->createAlbum($values, $track);

        $this->assertCount(0, $response->original->tracks);
    }

    /** @test */
    public function a_user_cannot_add_unpublished_tracks_to_his_albums()
    {
        $this->signin();

        $photo = UploadedFile::fake()->image('photo.jpg');

        $tag   = create(Tag::class);
        
        $tags[] = $tag->id;

        $values = [
            'details' => [
                'title' => 'My new Album',
            ],
            'photo' => $photo,
            'tags'  => $tags,
        ];

        $published = create(Track::class, [ 'user_id' => auth()->id(), 'published' => true ]);
        $notPublished = create(Track::class, [ 'user_id' => auth()->id() ]);

        $tracks = collect([ $published, $notPublished ]);

        $response = $this->createAlbum($values, $tracks)
            ->assertJson(['data' => [
                'type' => 'albums',
                'id'   => (string) $tracks->first()->id,
            ]]);

        $this->assertCount(1, $response->original->tracks);
    }

    /** @test */
    public function after_create_an_album_the_sended_tracks_should_be_related_to_the_album()
    {
        $this->signin();

        $photo = UploadedFile::fake()->image('photo.jpg');

        $tag   = create(Tag::class);
        
        $tags[] = $tag->id;

        $values = [
            'details' => [
                'title' => 'My new Album',
            ],
            'photo' => $photo,
            'tags'  => $tags,
        ];

        $tracks = create(Track::class, [ 'user_id' => auth()->id(), 'published' => true ], 2);

        $this->createAlbum($values, $tracks);

        $tracks->map(function ($track) {
            $this->assertDatabaseHas('tracks', [
                'id'       => $track->id,
                'user_id'  => auth()->id(),
                'album_id' => 1,
            ]);
        });
    }

    public function createAlbum($values, $tracks)
    {
        return $this->json('POST', '/api/albums', [
            'details' => $values['details'],
            'tracks'  => $tracks->pluck('id'),
            'tags'  => $values['tags'],
            'photo' => $values['photo'],
        ]);
    }
}
