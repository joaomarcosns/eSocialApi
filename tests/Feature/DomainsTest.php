<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
