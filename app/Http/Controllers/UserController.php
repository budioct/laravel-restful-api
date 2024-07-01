<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated(); // validasi

        $user = User::query()->where('username', $data['username'])->first();

        // jika user tidak ada, dan password tidak sama maka beri exception Unauthorization
        if (!$user || !Hash::check($data["password"], $user->password)){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString(); // set token dengan UUID jika user ada
        $user->save();

        return new UserResource($user);

    }

    public function getList(Request $request): UserResource
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        return new UserResource($user);

    }

}
