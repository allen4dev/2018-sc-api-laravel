<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Album;
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

        $this->followUser($user);

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

    public function followUser($user)
    {
        return $this->json('POST', $user->path() . '/follow');
    }
}
