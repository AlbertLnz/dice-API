<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_bad_register(){
        $response = $this->post(route('api.register'),[
            'email' => 'xxxx',
            'password' => 'sdsdsds'
        ]);
        $response->assertStatus(302); 
    }

    public function test_ok_register_being_anonymous(){

        $response = $this->post(route('api.register'),[
            'email' => fake()->unique()->email(),
            'password' => 'password'
        ]);
        
        $this->assertEquals($response->json('user')['name'], 'anonymous');
        $response->assertJsonStructure(['token'])->assertOk();
    }
}