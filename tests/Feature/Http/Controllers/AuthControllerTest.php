<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_register(): void
    {
        $response = $this->post('/api/v1/register',
            [
                'name' => 'testa',
                'email' => 'testa@test.test',
                'password' => '123456789',
                'password_confirmation' => '123456789',
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'token',
                    'token_type'
                ],
            );

        $response->dd();
    }

}
