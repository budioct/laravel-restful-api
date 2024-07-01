<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated(); // validasi

        // kodisi check username harus uniq
        if (User::query()->where("username", $data["username"])->count() == 1){
            // ada di database?
            // kembalikan respons bad request
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered."
                    ]
                ]
            ], 400));
        }

        $user = new User($data); // model impl attribute $fillable, supaya tidak binding data request api http dan web http dengan attribute model
        $user->password = Hash::make($data["password"]); // enkripsi password
        $user->save();

        return (new UserResource($user))
            ->response()->setStatusCode(201);

    }
}
