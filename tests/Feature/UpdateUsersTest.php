<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_update_profiles()
    {
        $this->json('PATCH', '/api/me', [])
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_update_his_profile()
    {
        $oldFields = [ 'username' => 'Archer' ];

        $user = create(User::class, $oldFields);
        
        $this->signin($user);

        $newFields = [ 'fullname' => 'Allen Walker', 'username' => 'Allen'  ];

        $this->json('PATCH', '/api/me', $newFields)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'type' => 'users',
                'id'   => (string) $user->id,
                'attributes' => [
                    'username' => $newFields['username'],
                    'fullname' => $newFields['fullname'],
                ],
            ]]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'username' => $oldFields['username'],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => $newFields['username'],
            'fullname' => $newFields['fullname'],
        ]);
    }
}
