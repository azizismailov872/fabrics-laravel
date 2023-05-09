<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {   
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($request->throttleKey());

            return response()->json([
                'email' => [
                    'message' => 'Неверный логин или пароль'
                ],
                'password' => [
                    'message' => 'Неверный логин или пароль'
                ]
            ])->setStatusCode(422);
        }
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    public function auth()
    {
        if(Auth::check()) {
            return response()->json([
                'auth' =>  Auth::check(),
                'user' => Auth::user()
            ],200);
        }
    
        return response()->json([
            'auth' =>  Auth::check()
        ],200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
