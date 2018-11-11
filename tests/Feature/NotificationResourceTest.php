<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Db;

use App\Track;
use App\User;

class NotificationResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->notifyUser($user1, $user2, 'POST,' . $user2->path() . '/follow');

        $response = $this->json('GET', '/api/me/notifications/' . $notification->id);
        
        $response->assertJson(['data' => [
            'type' => 'notifications',
            'id'   => (string) $notification->id,
        ]]);

        $response->assertJsonStructure(['data' => [
            'attributes' => [
                'message',
                'additional'  => [
                    'content',
                    'sender_username',
                ],
                'action',
                'created_at',
                'updated_at',
                'time_since',
            ]
        ]]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->notifyUser($user1, $user2, 'POST,' . $user2->path() . '/follow');

        $this->json('GET', '/api/me/notifications/' . $notification->id)
            ->assertJson(['data' => [
                'links' => [
                    'self' => route('notifications.show', [ 'id' => $notification->id ])
                ]
            ]]);
    }

    /** @test */
    public function a_collection_should_contain_a_list_of_notification_resources_under_a_data_object()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->notifyUser($user1, $user2, 'POST,' . $user2->path() . '/follow');

        $this->json('GET', '/api/me/notifications')
            ->assertJson(['data' => [
                [
                    'type' => 'notifications',
                    'id'   => (string) $notification->id,
                    'attributes' => [
                        'message' => "{$user1->username} has followed you",
                    ]
                ]
            ]]);
    }

    /** @test */
    public function a_collection_should_have_a_links_object_at_the_same_level_of_data_with_information_about_the_pagination()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->notifyUser($user1, $user2, 'POST,' . $user2->path() . '/follow');

        $this->json('GET', '/api/me/notifications')
            ->assertJson(['links' => [
                'self' => route('notifications.unread'),
            ]]);
    }

    /** @test */
    public function a_user_followed_notification_should_contain_a_message_with_the_name_who_followed_you_a_content_with_the_follower_username_and_additional_data()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->notifyUser($user1, $user2, 'POST,' . $user2->path() . '/follow');


        $this->json('GET', '/api/me/notifications/' . $notification->id)
            ->assertJson(['data' => [
                'type' => 'notifications',
                'id'   => (string) $notification->id,
                'attributes' => [
                    'message' => "{$user1->username} has followed you",
                    'additional'  => [
                        'content' => $user1->username,
                        'sender_username' => $user1->username,
                    ],
                    'action' => 'UserFollowed',
                    'created_at' => (string) $notification->created_at,
                    'updated_at' => (string) $notification->updated_at,
                    'time_since' => $notification->created_at->diffForHumans(),
                ]
            ]]);
    }

    /** @test */
    public function a_resource_published_notification_should_contain_a_message_with_the_user_who_published_a_content_with_the_publshed_track_name_under_the_additional_data()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $track = create(Track::class, [ 'user_id' => $user1->id ]);

        Db::table('followers')->insert([
            'follower_id'  => $user2->id,
            'following_id' => $user1->id,
        ]);

        $notification = $this->notifyUser($user1, $user2, 'PATCH,' . $track->path() . '/publish');

        $this->json('GET', '/api/me/notifications/' . $notification->id)
            ->assertJson(['data' => [
                'type' => 'notifications',
                'id'   => (string) $notification->id,
                'attributes' => [
                    'message'    => $user1->username . ' has published a new track.',
                    'additional' => [
                        'content' => $track->title,
                        'sender_username' => $user1->username,
                    ],
                    'action'     => 'ResourcePublished',
                    'created_at' => (string) $notification->created_at,
                    'updated_at' => (string) $notification->updated_at,
                    'time_since' => $notification->created_at->diffForHumans(),
                ],
            ]]);
    }

    public function notifyUser($user1, $user2, $route)
    {
        $action = explode(',', $route);

        $this->signin($user1);

        $this->json($action[0], $action[1]);

        auth()->logout();

        $this->signin($user2);

        return $user2->notifications()->first();
    }
}
