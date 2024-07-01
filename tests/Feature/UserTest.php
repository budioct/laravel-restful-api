<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

}
