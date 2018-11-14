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
}
