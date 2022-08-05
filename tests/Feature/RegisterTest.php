<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function makeUser()
    {
        return [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'email_confirm' => 'test@test.com',
            'password' => bcrypt('secret'),
            'password_confirm' => bcrypt('secret'),
        ];
    }
    /**
     * @test
     */
    public function name_should_be_required()
    {
        $user = $this->makeUser();
        $user['name'] = '';
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertSessionHasErrors([
            'name' => 'O Nome de usuário é obrigatório'
        ]);
    }
    /**
     * @test
     */
    public function name_should_have_a_max_of_255_characterisers()
    {
        $user = $this->makeUser();
        $user['name'] = str_repeat('a', 256);
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'name' => 'O Nome de usuário apenas pode ter max characters'
        ]);
    }
    /**
     * @test
     */
    public function name_should_be_string()
    {
        $user = $this->makeUser();
        $user['name'] = 123;
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'name' => 'Não pode conter números nesse campo'
        ]);
    }
}
