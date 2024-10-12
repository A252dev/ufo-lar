<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\UserBalance;

class ActionController extends Controller
{

    public function transfer(Request $request)
    {

        if (!empty(auth()->user()['email'])) {

            $validate = Validator::make($request->all(), [
                'toEmail' => 'required|email',
                'summa' => 'required',
                'currency' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid data',
                ]);
            }

            // Find the balance of target User
            $targetUserBalance = UserBalance::select()->where('email', '=', $request['toEmail'])->first();

            // Check target User in database
            if (!empty($targetUserBalance->email)) {

                $userBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();

                switch ($request['currency']) {
                    case 'USD':
                        if ($userBalance->usd <= 0 or $userBalance->usd < $request['summa']) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Not enough money',
                            ]);
                        } else {
                            $userBalance->usd -= $request['summa'];
                            $targetUserBalance->usd += $request['summa'];
                            $userBalance->save();
                            $targetUserBalance->save();
                        }
                        // $userBalance->save();
                        break;
                    case 'EUR':
                        $userBalance->eur -= $request['summa'];
                        $targetUserBalance->eur += $request['summa'];
                        // UserBalance::saved();
                        break;

                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transfer from ' . auth()->user()['email'] . ' to ' . $request['toEmail'] . ' in ' . $request['summa'] . ' ' . $request['currency'] . ' is completed!',
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ]);
            }




        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not authorized!',
            ]);
        }

    }

}
