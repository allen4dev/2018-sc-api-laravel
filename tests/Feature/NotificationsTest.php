<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
use App\Playlist;
use App\Reply;
use App\Track;
use App\User;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_is_notified_after_other_users_follow_him()
    {
        $this->signin();

        $user = create(User::class);

        $this->json('POST', $user->path() . '/follow');

        $this->assertCount(1, $user->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_users_who_he_is_following_publish_a_track()
    {
        $this->signin();
        
        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $follower = create(User::class);

        Db::table('followers')->insert([
            'follower_id'  => $follower->id,
            'following_id' => auth()->id(),
        ]);

        $this->json('PATCH', $track->path() . '/publish');
        
        $this->assertCount(1, $follower->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_users_who_he_is_following_publish_an_album()
    {
        $this->signin();
        
        $album = create(Album::class, [ 'user_id' => auth()->id() ]);

        $follower = create(User::class);

        Db::table('followers')->insert([
            'follower_id'  => $follower->id,
            'following_id' => auth()->id(),
        ]);

        $this->json('PATCH', $album->path() . '/publish');
        
        $this->assertCount(1, $follower->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_other_user_replies_his_track()
    {
        $this->signin();
        
        $track = create(Track::class, [ 'published' => true ]);
        
        $details = raw(Reply::class);

        $this->json('POST', $track->path() . '/replies', $details);

        $this->assertCount(1, $track->user->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_other_user_replies_his_reply()
    {
        $this->signin();
        
        $reply = create(Reply::class);
        
        $details = raw(Reply::class);

        $this->json('POST', $reply->path() . '/replies', $details);

        $this->assertCount(1, $reply->user->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_other_user_favorites_his_track()
    {
        $this->signin();

        $track = create(Track::class, [ 'published' => true ]);

        $this->json('POST', $track->path() . '/favorite');

        $this->assertCount(1, $track->user->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_other_user_favorites_his_album()
    {
        $this->signin();

        $album = create(Album::class, [ 'published' => true ]);

        $this->json('POST', $album->path() . '/favorite');

        $this->assertCount(1, $album->user->unreadNotifications);
    }

    /** @test */
    public function a_user_is_notified_after_other_user_favorites_his_playlist()
    {
        $this->signin();

        $playlist = create(Playlist::class);

        $this->json('POST', $playlist->path() . '/favorite');

        $this->assertCount(1, $playlist->user->unreadNotifications);
    }
}
