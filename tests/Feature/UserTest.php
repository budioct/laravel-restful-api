<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post("/api/users", [
            "username" => "budhi",
            "password" => "rahasia",
            "name" => "budhi octaviansyah"
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "username" => "budhi",
                    "name" => "budhi octaviansyah"
                ]
            ]);

    }

    public function testRegisterFailded()
    {
        $this->post("/api/users", [
            "username" => "",
            "password" => "",
            "name" => ""
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                    "name" => [
                        "The name field is required."
                    ]
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();

        $this->post("/api/users", [
            "username" => "budhi",
            "password" => "rahasia",
            "name" => "budhi octaviansyah"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "username already registered."
                    ]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post("/api/users/login", [
            "username" => "test",
            "password" => "test",
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => "test",
                    "name" => "test"
                ]
            ]);

        $user = User::query()->where("username", "test")->first();
        self::assertNotNull($user->token);
        Log::info(json_encode($user, JSON_PRETTY_PRINT));

    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current",[
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => "test",
                    "name" => "test",
                ]
            ]);

    }

    public function testUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        // tidak set "Authorization" => "test"
        $this->get("/api/users/current")
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);

    }

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current",[
            "Authorization" => "salah"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);

    }

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);

        $oldUser = User::query()->where("username", "=", "test")->first();

        $this->patch("/api/users/current",
            [
                "name" => "budhi"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => "test",
                    "name" => "budhi",
                ]
            ]);

        $newUser = User::query()->where("username", "=", "test")->first();
        self::assertNotEquals($oldUser->name, $newUser->name);

    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);

        $oldUser = User::query()->where("username", "=", "test")->first();

        $this->patch("/api/users/current",
            [
                "password" => "baru"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => "test",
                    "name" => "test",
                ]
            ]);

        $newUser = User::query()->where("username", "=", "test")->first();
        self::assertNotEquals($oldUser->password, $newUser->password);

    }

    public function testUpdatePasswordFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->patch('/api/users/current',
            [
                'name' => 'asekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasekasek'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        "The name field must not be greater than 100 characters."
                    ]
                ]
            ]);

    }

}
