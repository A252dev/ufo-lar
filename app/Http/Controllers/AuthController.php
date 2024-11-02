<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\UserBalance;


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
            'birthday' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ]);
        }

        // Create a new user
        $user = new User();
        // $user = new User($request['email'], $request['firstName'], $request['secondName'], bcrypt($request['password']));
        $user->email = $request['email'];
        $user->firstName = $request['firstName'];
        $user->secondName = $request['secondName'];
        $user->birthday = $request['birthday'];
        $user->password = bcrypt($request['password']);
        $user->save();

        // Create a user balance
        $userBalance = new UserBalance();
        // $userBalance->create();
        $userBalance->create($request['email'], 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0);

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
                'message' => 'Invalid data'
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
