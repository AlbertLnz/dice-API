<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    //REGISTER (INCORRECT)
    public function test_bad_register(){
        $response = $this->post(route('api.register'),[
            'email' => 'xxxx',
            'password' => 'sdsdsds'
        ]);
        $response->assertStatus(302); 
    }

    //REGISTER (CORRECT)
    public function test_ok_register_being_anonymous(){

        $response = $this->post(route('api.register'),[
            'email' => fake()->unique()->email(),
            'password' => 'password'
        ]);
        
        $this->assertEquals($response->json('user')['name'], 'anonymous');
        $response->assertJsonStructure(['token'])->assertOk();
    }

    //LOGIN (INCORRECT)
    public function test_bad_login(){
        $user = User::factory()->create();

        $response = $this->postJson(route('api.login'),[
            'email' => $user->email,
            'password' => "incorrect_password"
        ]);
        
        $response->assertJsonStructure([])->assertStatus(401); //no token in Json

    }

    //LOGIN (CORRECT)
    public function test_ok_login(){

        $user = User::factory()->create();

        $response = $this->postJson(route('api.login'),[
            'email' => $user->email,
            'password' => "password"
        ]);
        
        $response->assertJsonStructure(['token'])->assertOk();
    }

    //INDEX (INCORRECT)
    public function test_bad_index_no_permission_not_logged(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('api.players.index'));
        $response->assertStatus(401);
    }
    public function test_bad_index_no_permission_role_client(){
        $user = User::factory()->create()->assignRole('client');

        Passport::actingAs($user); // ¿token???: $token = $user->createToken('Personal Access Token')->accessToken;

        $response = $this->actingAs($user)->getJson(route('api.players.index'));
        $response->assertStatus(403);
    }
    
    // INDEX (CORRECT)
    public function test_ok_index_permission_role_admin(){
        $user = User::factory()->create()->assignRole('admin');

        Passport::actingAs($user); // ¿token???: $token = $user->createToken('Personal Access Token')->accessToken;

        $response = $this->actingAs($user)->getJson(route('api.players.index'));
        $response->assertStatus(200);

    }

    //SHOW (CORRECT)
    public function test_show_correct_user(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $this->get(route('api.players.show' , $user->id))->assertOk()->assertJsonStructure([
            'Your win rate',
            'Games'
        ]);
    }

    //SHOW (INCORRECT)
    public function test_show_incorrect_user(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $otherId = User::inRandomOrder()->where('id', '!=', $user->id)->first()->id;

        $this->get(route('api.players.show' , $otherId))->assertOk()->assertJsonStructure([
            'error'
        ]);
    }




}