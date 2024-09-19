<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed',
            'username'      => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'username'      => $request->username,
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'data'    => $user,
                'message' => 'Register success'
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }
}
