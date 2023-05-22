<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class UserTest extends TestCase
{
    public function test_register(){

        $carga = $this->post(route('api.register'),[
            "email" => 'xxxx',
            'password' => 'sdsdsds'
        ]);

        $carga->assertStatus(302);

    }
}
