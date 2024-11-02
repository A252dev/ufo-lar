<?php

namespace App\Http\Controllers;
use \App\Models\UserBalance;


class ProfileController extends Controller
{

    private function getUserBalance()
    {
        return UserBalance::where("email", auth()->user()->email)->get();
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => auth()->user(),
                'balance' => $this->getUserBalance(),
            ],
        ]);
    }


}
