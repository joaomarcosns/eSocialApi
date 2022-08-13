<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Laravel\Passport\Passport;

class DomainsTest extends TestCase
{
    /**
     * @test
     */
    public function check_if_the_user_is_logged()
    {

        $return =  $this->get(route('domains.index'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        $return->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }
    /**
     * @test
     */
    public function file_should_be_required()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $return =  $this->post(route('domains.upload'), [
            'Accept' => 'application/json',
            'Content-Type' => 'multipart/form-data',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            'file' => ''
        ]);

        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'file' => 'O arquivo é obrigatório'
        ]);
    }
}
