<?php

namespace Tests\Feature;

use App\Models\Domains;
use App\Models\Registers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Laravel\Passport\Passport;

class DomainsTest extends TestCase
{
    public function makeDomains()
    {
        return [
            "register" => "Alexandre Faustino",
            "name" => "Testt0001",
            "tld" => "test.test",
            "created_at" => "2022-08-14",
            "updated_at" => "2022-08-14",
            "nameserver_1" => "1888.teste.com", 
            "nameserver_2" => "1889.teste.com"
        ];
    }
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

    /**
     * @test
     */
    public function file_should_be_extension_incorrect()
    {
        Storage::fake('avatars');
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $return =  $this->post(route('domains.upload'), [
            'Accept' => 'application/json',
            'Content-Type' => 'multipart/form-data',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            'file' => 'avatars.jpg'
        ]);

        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'file' => 'O extensão deve incorreta, dev ser .xlsx'
        ]);
    }

    /**
     * @test
     */
    public function domain_name_should_be_required()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['name'] = '';
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'name' => 'O nome do domínio é obrigatório'
        ]);
    }

    /**
     * @test
     */
    public function domain_tld_should_be_required()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['tld'] = '';
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'tld' => 'O tld do domínio é obrigatório'
        ]);
    }
    /**
     * @test
     */
    public function domain_name_should_be_max()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['name'] = str_repeat('a', 256);
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'name' => 'O tamanho máximo do nome do é de 255'
        ]);
    }
    /**
     * @test
     */
    public function domain_tld_should_be_max()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['tld'] = str_repeat('a', 256);
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'tld' => 'O tamanho máximo do tld do é de 15'
        ]);
    }
    /**
     * @test
     */
    public function domain_name_should_be_unique()
    {
        $registers = Registers::create([
            "name" => "TEtse 0001",
        ]);
        
        Domains::create([
            "name" => "Testt0001",
			"tld" => "test.test",
			"created_at" => "2022-08-14",
			"updated_at" => "2022-08-14",
			"expiration_date" => "2023-08-14",
			"fk_registers_id" => $registers->id,
        ]);
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['name'] = 'Testt0001';
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'name' => 'Nome do domínio já existe'
        ]);
    }
    /**
     * @test
     */
    public function register_name_should_be_required()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['register'] = '';
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'register' => 'O nome do registrador é obrigatório'
        ]);
    }
    /**
     * @test
     */
    public function register_name_should_be_max_255_characters()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['register'] = str_repeat('a', 256);
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'register' => 'O tamanho máximo do nome do registrador é 255'
        ]);
    }
    /**
     * @test
     */
    public function domains_created_at_should_be_required()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['created_at'] = '';
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'created_at' => 'O campo é obrigatório'
        ]);
    }
    /**
     * @test
     */
    public function domains_updated_at_should_be_required()
    {
        Passport::actingAs(
            $user  = User::factory()->createOne(),
            ['create-servers']
        );
        $domain = $this->makeDomains();
        $domain['updated_at'] = '';
        $return =  $this->post(route('domains.store'), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$user->createToken('Personal Access Token')->accessToken}",
            "register" => $domain['register'],
            "name" => $domain['name'],
            "tld" => $domain['tld'],
            "created_at" => $domain['created_at'],
            "updated_at" => $domain['updated_at'],
            "nameserver_1" => $domain['nameserver_1'], 
            "nameserver_2" => $domain['nameserver_2']
        ]);
        $return->assertStatus(302);
        $return->assertSessionHasErrors([
            'updated_at' => 'O campo é obrigatório'
        ]);
    }
}
