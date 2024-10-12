<?php

namespace App\Http\Controllers;


class ProfileController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => auth()->user(),
        ]);
    }


}
