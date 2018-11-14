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

        $image = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', '/api/me/avatar', compact('image'));
        
        Storage::disk('public')->assertExists('avatars/' . $image->hashName());

        $this->assertDatabaseHas('users', [
            'id' => auth()->id(),
            'avatar_url' => 'avatars/' . $image->hashName(),
        ]);
    }
}
