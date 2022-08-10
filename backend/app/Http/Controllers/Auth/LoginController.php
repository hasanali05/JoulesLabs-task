<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // validation rules.
        $request->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if(!auth()->attempt($request->only('email', 'password'))){
            // return response()->json(['errors' => ['email' => ['invalid credential.']]], 422);
            throw new AuthenticationException();
        }
        $user = auth()->user();

        $user->token = $user->createToken('web')->plainTextToken;
        return $user;
    }
}
