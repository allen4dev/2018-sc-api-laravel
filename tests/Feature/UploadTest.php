<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        
        Storage::disk('public')->assertExists('avatars/' . $avatar->hashName());

        $this->assertDatabaseHas('users', [
            'id' => auth()->id(),
            'avatar_url' => 'avatars/' . $avatar->hashName(),
        ]);
    }

    /** @test */
    public function a_user_can_upload_a_profile_image()
    {
        Storage::fake();

        $this->signin();

        $profile = UploadedFile::fake()->image('profile_image.jpg');

        $this->json('PATCH', '/api/me', compact('profile'));
        
        Storage::disk('public')->assertExists('profile/' . $profile->hashName());

        $this->assertDatabaseHas('users', [
            'id' => auth()->id(),
            'profile_image' => 'profile/' . $profile->hashName(),
        ]);
    }

    /** @test */
    public function a_user_should_also_send_a_photo_for_the_track()
    {
        Storage::fake();

        $this->signin();

        $photo = UploadedFile::fake()->image('my_track.jpg');

        $details = [
            'title' => 'My awesome track',
            'photo' => $photo,
        ];

        $this->json('POST', '/api/tracks', $details);
        
        Storage::disk('public')->assertExists('tracks/' . $photo->hashName());

        $this->assertDatabaseHas('tracks', [
            'user_id' => auth()->id(),
            'photo'   => 'tracks/' . $photo->hashName(),
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
        
        Storage::disk('public')->assertExists('albums/' . $photo->hashName());

        $this->assertDatabaseHas('albums', [
            'user_id' => auth()->id(),
            'photo'   => 'albums/' . $photo->hashName(),
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
        
        Storage::disk('public')->assertExists('playlists/' . $photo->hashName());

        $this->assertDatabaseHas('playlists', [
            'user_id' => auth()->id(),
            'photo'   => 'playlists/' . $photo->hashName(),
        ]);
    }
}
