<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
    public function test_failed_registration()
    {

        $user = [
            'name' => "gabbi13",
//            'email' => 'gabby13@gmail.com',
            'password' => 'passwordD123',
            'roles' => 'admin',
            'verification_token' => Str::random(10)
        ];

        $response = $this->post('api/v1/register', $user);
        $response->assertStatus(400);
        $response->assertJson([
            "status"=>'Failed'
        ]);
    }

}
