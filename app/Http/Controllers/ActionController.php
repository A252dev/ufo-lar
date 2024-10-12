<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\UserBalance;

class ActionController extends Controller
{

    public function transfer(Request $request)
    {

        // Check the auth of User (Me)
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

                // Find the balance of Me
                $userBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();

                // Error message
                $errorMessage = 'Not enough money';

                // Tranfer money with currect currency
                switch ($request['currency']) {
                    case 'USD':
                        if ($userBalance->USD <= 0 or $userBalance->USD < $request['summa']) {
                            return response()->json([
                                'status' => 'error',
                                'message' => $errorMessage,
                            ]);
                        } else {
                            $userBalance->USD -= $request['summa'];
                            $targetUserBalance->USD += $request['summa'];
                            $userBalance->save();
                            $targetUserBalance->save();
                        }
                        break;
                    case 'EUR':
                        if ($userBalance->EUR <= 0 or $userBalance->EUR < $request['summa']) {
                            return response()->json([
                                'status' => 'error',
                                'message' => $errorMessage,
                            ]);
                        } else {
                            $userBalance->EUR -= $request['summa'];
                            $targetUserBalance->EUR += $request['summa'];
                            $userBalance->save();
                            $targetUserBalance->save();
                        }
                        break;
                    default:
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Currency is not selected!',
                        ]);
                }

                // Return the success message
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transfer from ' . auth()->user()['email'] . ' to ' . $request['toEmail'] . ' in ' . $request['summa'] . ' ' . $request['currency'] . ' is completed!',
                ]);

            } else {

                // Return the error message if user not found
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ]);
            }

        } else {
            // Return message that user is not authorized
            return response()->json([
                'status' => 'error',
                'message' => 'User is not authorized!',
            ]);
        }

    }

}
