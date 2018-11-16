<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Tag;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_upload_an_avatar_image()
    {
        Storage::fake();

        $this->signin();

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $this->json('PATCH', '/api/me', compact('avatar'));
        
        $uploadedPath = 'users/avatars/' . $avatar->hashName();

        Storage::disk('public')->assertExists($uploadedPath);

        $this->assertDatabaseHas('users', [
            'id' => auth()->id(),
            'avatar_url' => $uploadedPath,
        ]);
    }

    /** @test */
    public function a_user_can_upload_a_profile_image()
    {
        Storage::fake();

        $this->signin();

        $profile = UploadedFile::fake()->image('profile_image.jpg');

        $this->json('PATCH', '/api/me', compact('profile'));
        
        $uploadedPath = 'users/profile/' . $profile->hashName();

        Storage::disk('public')->assertExists($uploadedPath);

        $this->assertDatabaseHas('users', [
            'id' => auth()->id(),
            'profile_image' => $uploadedPath,
        ]);
    }

    /** @test */
    public function a_user_should_also_send_a_photo_for_the_track()
    {
        Storage::fake();

        $this->signin();

        $tag = create(Tag::class);
        
        $photo = UploadedFile::fake()->image('my_track.jpg');
        $src = UploadedFile::fake()->create('song.mp3');

        $tags[] = $tag->id;

        $details = [
            'title' => 'My awesome track',
            'photo' => $photo,
            'src'   => $src,
            'tags'  => $tags,
        ];

        $this->json('POST', '/api/tracks', $details);
        
        $uploadedPath = 'tracks/photos/' . $photo->hashName();

        Storage::disk('public')->assertExists($uploadedPath);

        $this->assertDatabaseHas('tracks', [
            'user_id' => auth()->id(),
            'photo'   => $uploadedPath,
        ]);
    }

    /** @test */
    public function a_user_should_send_an_audio_file_for_the_track()
    {
        Storage::fake();

        $this->signin();

        $tag = create(Tag::class);
        
        $photo = UploadedFile::fake()->image('my_track.jpg');
        $src = UploadedFile::fake()->create('song.mp3');

        $tags[] = $tag->id;

        $details = [
            'title' => 'My awesome track',
            'photo' => $photo,
            'src'   => $src,
            'tags'  => $tags,
        ];

        $this->json('POST', '/api/tracks', $details);
        
        $uploadedPath = 'tracks/audio/' . $src->hashName();

        Storage::disk('public')->assertExists($uploadedPath);

        $this->assertDatabaseHas('tracks', [
            'user_id' => auth()->id(),
            'src'   => $uploadedPath,
        ]);
    }

    /** @test */
    public function a_user_should_also_send_a_photo_for_the_album()
    {
        Storage::fake();

        $this->signin();

        $photo = UploadedFile::fake()->image('my_album.png');

        $input = [
            'details' => [ 'title' => 'My awesome album' ],
            'photo' => $photo,
            'tracks' => [ 1, 2, 4 ],
        ];

        $this->json('POST', '/api/albums', $input);
        
        $uploadedPath = 'albums/photos/' . $photo->hashName();

        Storage::disk('public')->assertExists($uploadedPath);

        $this->assertDatabaseHas('albums', [
            'user_id' => auth()->id(),
            'photo'   => $uploadedPath,
        ]);
    }

    /** @test */
    public function a_user_should_also_send_a_photo_for_the_playlist()
    {
        Storage::fake();

        $this->signin();

        $photo = UploadedFile::fake()->image('my_playlist.jpg');

        $details = [
            'title' => 'My awesome playlist',
            'photo' => $photo,
        ];

        $this->json('POST', '/api/playlists', $details);
        
        $uploadedPath = 'playlists/photos/' . $photo->hashName();

        Storage::disk('public')->assertExists($uploadedPath);

        $this->assertDatabaseHas('playlists', [
            'user_id' => auth()->id(),
            'photo'   => $uploadedPath,
        ]);
    }
}
