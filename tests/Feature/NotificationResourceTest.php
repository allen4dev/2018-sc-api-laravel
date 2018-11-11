<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class NotificationResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->followAndNotify($user1, $user2);

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
            ]
        ]]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->followAndNotify($user1, $user2);

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

        $notification = $this->followAndNotify($user1, $user2);

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

        $this->followAndNotify($user1, $user2);

        $this->json('GET', '/api/me/notifications')
            ->assertJson(['links' => [
                'self' => route('notifications.unread'),
            ]]);
    }

    /** @test */
    public function a_user_followed_notification_should_contain_a_custom_message_and_additional_data()
    {
        $user1 = create(User::class);
        $user2 = create(User::class);

        $notification = $this->followAndNotify($user1, $user2);


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
                ]
            ]]);
    }

    public function followAndNotify($user1, $user2)
    {
        $this->signin($user1);

        $this->json('POST', $user2->path() . '/follow');

        auth()->logout();

        $this->signin($user2);

        return $user2->notifications()->first();
    }
}
