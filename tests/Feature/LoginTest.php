<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function makeUser()
    {
        return [
            'email' => 'test@test.com',
            'password' => bcrypt('secret'),
        ];
    }
    /**
     * @test
     */
    public function email_should_be_required()
    {
        $user = $this->makeUser();
        $user['email'] = '';
        $return =  $this->post(route('auth.login'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'email' => 'O campo de e-mail é obrigatório'
        ]);
    }

    /**
     * @test
     */
    public function email_should_be_valid_email()
    {
        $user = $this->makeUser();
        $user['email'] = 'any';
        $return =  $this->post(route('auth.login'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'email' => 'O campo de e-mail deve ser do tipo e-mail'
        ]);
    }
    /**
     * @test
     */
    public function password_should_be_required()
    {
        $user = $this->makeUser();
        $user['password'] = '';
        $return =  $this->post(route('auth.login'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'password' => 'O campo senha é obrigatório'
        ]);
    }

}
