<?php

namespace Tests\Feature;

use App\Models\User;
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
            'email_confirmation' => 'test@test.com',
            'password' => bcrypt('secret'),
            'password_confirmation' => bcrypt('secret'),
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
            'name' => 'O Nome de usuário apenas pode ter 255 characters'
        ]);
    }

    /**
     * @test
     */
    public function name_should_have_a_min_of_3_characterisers()
    {
        $user = $this->makeUser();
        $user['name'] = str_repeat('a', 2);
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'name' => 'O Nome de usuário tem ser maior que 3 characters'
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
    /**
     * @test
     */
    public function email_should_be_required()
    {
        $user = $this->makeUser();
        $user['email'] = '';
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
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
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
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
    public function email_should_be_valid_unique()
    {
        User::factory()->create(['email' => 'test@test.com']);
        $user = $this->makeUser();
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'email' => 'Essa conta já existe'
        ]);
    }
    /**
     * @test
     */
    public function email_should_be_confirmed()
    {
        $user = $this->makeUser();
        $user['email'] = 'teste@any.com';
        $user['email_confirmation'] = 'any@any.com';
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'email_confirmation' => $user['email_confirmation'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'email' => 'A confirmação do e-mail não corresponde.'
        ]);
    }
    /**
     * @test
     */
    public function password_should_be_required()
    {
        $user = $this->makeUser();
        $user['email'] = 'teste@any.com';
        $user['email_confirmation'] = 'teste@any.com';
        $user['password'] = '';
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'email_confirmation' => $user['email_confirmation'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'password' => 'O campo senha é obrigatório'
        ]);
    }
    /**
     * @test
     */
    public function password_should_have_a_min_of_6_characterisers()
    {
        $user = $this->makeUser();
        $user['email'] = 'teste@any.com';
        $user['email_confirmation'] = 'teste@any.com';
        $user['password'] = 123;
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'password' => 'A confirmação da senha não corresponde.'
        ]);
    }
    /**
     * @test
     */
    public function password_should_be_confirmed()
    {
        $user = $this->makeUser();
        $user['email'] = 'teste@any.com';
        $user['email_confirmation'] = 'teste@any.com';
        $user['password_confirmation'] = bcrypt('teste');
        $return =  $this->post(route('auth.register'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'name' => $user['name'],
            'email' => $user['email'],
            'email_confirmation' => $user['email_confirmation'],
            'password' => $user['password'],

        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'password' => 'A confirmação da senha não corresponde.'
        ]);
    }
}
