<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'firstName' => 'required',
            'secondName' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ]);
        }

        $user = new User();
        $user->email = $request['email'];
        $user->firstName = $request['firstName'];
        $user->secondName = $request['secondName'];
        $user->password = bcrypt($request['password']);
        $user->save();

        $userBalance = new UserBalance();
        $userBalance->email = $request['email'];
        $userBalance->usd = 0.0;
        $userBalance->eur = 0.0;
        $userBalance->save();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function login()
    {

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorised'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User logged successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'User is successfully logout!',
        ]);
    }

    public function profile()
    {
        return response()->json([
            'status' => 'success',
            'data' => auth()->user(),
        ]);
    }
}
