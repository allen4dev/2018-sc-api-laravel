<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Album;
use App\Playlist;
use App\Reply;
use App\Track;
use App\User;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_users_profiles()
    {
        $this->json('DELETE', '/api/users/1')
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_delete_his_profile()
    {
        $this->signin();

        tap(auth()->user(), function ($user) {
            $this->assertDatabaseHas('users', [
                'id' => auth()->id(),
                'email' => $user->email,
                'username' => $user->username,
            ]);
    
            $this->json('DELETE', $user->path())
                ->assertStatus(204);
    
            $this->assertDatabaseMissing('users', [
                'id' => auth()->id(),
                'email' => $user->email,
                'username' => $user->username,
            ]);
        });
    }

    /** @test */
    public function only_the_owner_of_a_profile_can_delete_his_profile()
    {
        $this->signin();

        $user = create(User::class);
        
        $this->json('DELETE', $user->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
        ]);
    }

    /** @test */
    public function after_delete_a_user_his_tracks_should_also_be_deleted()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', auth()->user()->path());

        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
            'user_id' => auth()->id(),
        ]);
    }

    /** @test */
    public function after_delete_a_user_his_albums_should_also_be_deleted()
    {
        $this->signin();

        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', auth()->user()->path());

        $this->assertDatabaseMissing('albums', [
            'id' => $album->id,
            'user_id' => auth()->id(),
        ]);
    }

    /** @test */
    public function after_delete_a_user_his_playlists_should_also_be_deleted()
    {
        $this->signin();

        $reply = create(Reply::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', auth()->user()->path());

        $this->assertDatabaseMissing('playlists', [
            'id' => $reply->id,
            'user_id' => auth()->id(),
        ]);
    }

    /** @test */
    public function after_delete_a_user_his_replies_should_also_be_deleted()
    {
        $this->signin();

        $reply = create(Reply::class, [ 'user_id' => auth()->id() ]);

        $this->json('DELETE', auth()->user()->path());

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
            'user_id' => auth()->id(),
        ]);
    }
}
