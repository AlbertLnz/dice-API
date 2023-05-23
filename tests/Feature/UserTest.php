<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;

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

}