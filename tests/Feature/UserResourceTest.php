<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Track;
use App\User;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $this->withoutExceptionHandling();
        
        $user = create(User::class);

        $this->json('GET', $user->path())
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'id'   => (string) $user->id,
                    'attributes' => [
                        'username' => $user->username,
                        'email'    => $user->email,
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_user_identifier_should_contain_a_data_with_a_type_and_the_id_of_the_user()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', $track->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'user' => [
                            'data' => [ 'type' => 'users', 'id' => (string) auth()->id() ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_user_identifier_should_contain_a_links_object_containing_a_url_to_the_user_path()
    {
        $this->signin();

        $track = create(Track::class, [ 'user_id' => auth()->id() ]);

        $this->json('GET', $track->path())
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'user' => [
                            'links' => [
                                'self' => route('users.show', [ 'id' => auth()->id() ])
                            ]
                        ]
                    ]
                ]
            ]);
    }
}