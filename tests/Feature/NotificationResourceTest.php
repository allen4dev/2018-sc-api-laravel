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

        $this->signin($user1);

        $user2 = create(User::class);

        $this->json('POST', $user2->path() . '/follow');

        auth()->logout();

        $this->signin($user2);

        $notification = $user2->notifications()->first();

        $this->json('GET', '/api/me/notifications/' . $notification->id)
            ->assertJson(['data' => [
                'type' => 'notifications',
                'id'   => (string) $notification->id,
                'attributes' => [
                    'message'     => "{$user1->username} has followed you",
                    'additional'  => [
                        'content' => $user1->username,
                        'sender_username' => $user1->username,
                    ],
                    'action'     => 'UserFollowed',
                    'created_at' => (string) $notification->created_at,
                    'updated_at' => (string) $notification->updated_at
                ],
            ]]);
    }

    /** @test */
    public function it_should_contain_a_links_object_with_a_self_url_link_under_a_data_object()
    {
        $user1 = create(User::class);

        $this->signin($user1);

        $user2 = create(User::class);

        $this->json('POST', $user2->path() . '/follow');

        auth()->logout();

        $this->signin($user2);

        $notification = $user2->notifications()->first();

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

        $this->signin($user1);

        $user2 = create(User::class);

        $this->json('POST', $user2->path() . '/follow');

        auth()->logout();

        $this->signin($user2);

        $notification = $user2->notifications()->first();

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
}
