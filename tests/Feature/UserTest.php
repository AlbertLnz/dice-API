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

        return $response->json(['token']);
    }

    //INDEX (INCORRECT)
    public function test_bad_index_no_permission_not_logged(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('api.players.index'));

        $response->assertStatus(401);
    }
    public function test_bad_index_no_permission_role_client(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $response = $this->actingAs($user)->getJson(route('api.players.index'));
        $response->assertStatus(403);
    }
    
    // INDEX (CORRECT)
    public function test_ok_index_permission_role_admin(){
        $user = User::factory()->create()->assignRole('admin');
        Passport::actingAs($user);

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

        $this->get(route('api.players.show' , $otherId))->assertStatus(401)->assertJsonStructure([
            'error'
        ]);
    }

    //STORE (CORRECT)
    public function test_store_correct_id_play_game(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $this->post(route('api.players.store', $user->id))->assertOk()->assertJsonStructure([
            'game'
        ]);
    }

    //STORE (INCORRECT)
    public function test_store_incorrect_id_not_play_game(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $otherId = User::inRandomOrder()->where('id', '!=', $user->id)->first()->id;

        $this->post(route('api.players.store' , $otherId))->assertStatus(401)->assertJsonStructure([
            'error'
        ]);

    }

    //UPDATE (CORRECT)
    public function test_update_user_correctly(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $response = $this->putJson(route('api.players.update', $user->id),[
            'name' => 'xxxxxx',
            'email' => fake()->unique()->email(),
            'password' => 'password',
        ]);

        $response->assertOk();

    }

    //UPDATE (INCORRECT)
    public function test_update_user_incorrectly_wrong_data_no_name(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $response = $this->putJson(route('api.players.update', $user->id),[
            'email' => fake()->unique()->email(),
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    //DESTROY (CORRECT)
    public function test_destroy_user_games_correctly(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $response = $this->delete(route('api.players.destroy', $user->id))->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);
    }

    //DESTROY (INCORRECT)
    public function test_destroy_other_user_games(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $otherId = User::inRandomOrder()->where('id', '!=', $user->id)->first()->id;

        $response = $this->delete(route('api.players.destroy', $otherId))->assertJsonStructure([
            'error'
        ]);

        $response->assertStatus(401);
    }

    //LOGOUT (CORRECT)
    public function test_logout_correctly(){
        $token = $this->test_ok_login();

        $response = $this->post(route('api.logout'), [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertOk();
    }

    //LOGOUT (INCORRECT)
    public function test_logout_incorrectly_user_not_logged(){
        $user = User::factory()->create()->assignRole('client');
        Passport::actingAs($user);

        $this->post(route('api.logout'))->assertStatus(500);
    }

    //RANKINGS
    public function test_general_ranking_access(){
        $this->get(route('api.players.generalRanking'))->assertJsonStructure([
            'Average win rate of users'
        ])->assertOk();
    }

    public function test_winner_ranking_access(){
        $this->get(route('api.players.winnerRanking'))->assertOk();
    }

    public function test_loser_ranking_access(){
        $this->get(route('api.players.loserRanking'))->assertOk();
    }
}