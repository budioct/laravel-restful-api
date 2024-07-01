<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->header("Authorization"); // ambil value dari key header Authorization

        $authenticate = true; // set nilai awal untuk conditional

        if (!$token) {
            $authenticate = false; // jika token tidak ada maka set false
        }

        $user = User::where("token", "=", $token)->first(); // query db where header dengan column token
        if (!$user){
            $authenticate = false;
        } else{
            // di panggil ketika sukses

            // package Illuminate\Support\Facades\Auth dari laravel
            // gunakan method static login(model).. untuk registrasikan model ini ke SESSION/Request
            // tapi ini akan error, jadi kita perlu implements Authenticatable di model User supaya di middlware ini di support
            Auth::login($user);
        }

        // jika token ada boleh lewat middleware
        // jika token tidak ada akan exception response "Unauthorized"
        if ($authenticate) {
            return $next($request);
        } else {
            return response()->json([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ])->setStatusCode(401);
        }
    }

}
