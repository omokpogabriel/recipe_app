<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use refreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_passed_registration()
    {

        $user = [
            'name' => "gabbi13",
            'email' => 'gabby13@gmail.com',
            'password' => 'passwordD123',
            'roles' => 'admin',
            'verification_token' => Str::random(10)
        ];

            $response = $this->post('api/v1/register', $user);
                $response->assertStatus(200);
                $response->assertJsonMissing([
                    "status"=>'Failed'
                ]);

    }

    public function test_login(){

        User::factory(1)->create();

        $data =
            ["email" => "admin1@admin.com",
            "password" => "passwordD123"
        ];

        $response = $this->call('POST','api/v1/login',$data);
        $response->assertOk();
        $response->assertJsonMissing(['status'=>'Failed']);
        $response->assertJson(['status'=>'success']);
    }

    public function test_verify_account(){
        User::factory(1)->state(
            [
                'name' => "gabbi13",
                'email' => 'gabby13@gmail.com',
                'password' => 'passwordD123',
                'roles' => 'admin',
                'verification_token' => 'iamahappyman'
            ])->create();
        $response  = $this->get('http://127.0.0.1:8000/api/v1/verify_account/iamahappyman');
        $response->assertOk();
        $response->assertStatus(200);
        $response->assertJsonFragment(['verified' => "Account verifed success, please login"]);
    }

    public function test_change_password(){
            Sanctum::actingAs(
               User::factory(1)->state(
                   [
                       'name' => "gabbi13",
                       'email' => 'gabby13@gmail.com',
                       'password' => 'passwordD123',
                       'roles' => 'admin',
                       'verification_token' => 'iamahappyman'
                   ])->create(), ['']
            );
        $data= [ 'new_password' => '12334674674764' ];

        $response = $this->post('http://127.0.0.1:8000/api/v1/changepassword', $data);
        $response->assertOk();
    }

}
