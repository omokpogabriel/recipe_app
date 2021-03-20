<?php

namespace Tests\Feature;


use App\Models\User;
use Faker\Provider\DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use refreshDatabase;

    private function user(): array{
        return  [
            'name' => "gabbi13",
            'email' => 'gabby13@gmail.com',
            'password' => 'passwordD123',
            'roles' => 'admin',
            'verification_token' => 'iamahappyman'
        ];
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_passed_registration()
    {

        $user = $this->user();

            $response = $this->post('api/v1/register', $user);
                $response->assertStatus(200);
                $response->assertJsonMissing([
                    "status"=>'Failed'
                ]);

    }

    public function test_login(){

        User::factory(1)->state([
            "email" => "gabby13@gmail.com",
            "email_verified_at" => Date::now(),
            "isActive" => true
        ])->create();

        $data = ["email" => "gabby13@gmail.com",
            "password" => "passwordD123"
        ];

        $response = $this->call('POST','api/v1/login',$data);
        $response->assertOk();
        $response->assertJsonMissing(['status'=>'Failed']);
        $response->assertJson(['status'=>'success']);
    }

    public function test_verify_account(){
          $user = $this->user();
            $user['email'] = 'omokpogabriel@gmail.com';
            $user['name'] = 'omokpogabriel';
            $user['email_verified_at'] = null;
           User::factory(1)->state($user)->create();


        $response  = $this->get("http://127.0.0.1:8000/api/v1/verify_account/{$user['verification_token']}");
        $response->assertOk();
        $response->assertStatus(200);
        $response->assertJsonFragment(['verified' => "Account verified success, please login"]);
    }

//    public function test_change_password(){
//
//            Sanctum::actingAs(
//                User::factory(1)->state([
//                    'name' => "gabbi131",
//                    'email' => 'gabby131@gmail.com',
//                    'password' => 'passwordD123',
//                    'verification_token' => 'iamahappyman',
//                    'isActive' => true,
//                    'email_verified_at' => DateTime::dateTime()
//                ])->create()
//            );
//
//        $data= [ 'new_password' => '12334674674764' ];
//
//        $response = $this->post('http://127.0.0.1:8000/api/v1/changepassword', $data);
//        $response->assertOk();
//    }

}
