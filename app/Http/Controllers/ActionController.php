<?php

namespace App\Http\Controllers;

// Imports
use Illuminate\Http\Request;
use Validator;
use App\Models\UserBalance;
use Brick\Money\Money;
use Brick\Math\RoundingMode;

class ActionController extends Controller
{

    public function setBalanceAmount($currency, $value)
    {
        $getActualBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();
        $getActualBalance->$currency = Money::of($value, $currency)->plus($value)->getAmount();
        $getActualBalance->save();
    }

    public function getBalanceAmount()
    {
        return UserBalance::select()->where('email', '=', value: auth()->user()['email'])->first();
    }

    // Remove the My balance
    public function removeBalanceAmount($currency, $amount)
    {
        $getActualBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();
        $getActualBalance->$currency = Money::of($amount, $currency)->minus($amount)->getAmount();
        $getActualBalance->save();
    }

    public function setBalanceToTargetUser($toEmail, $amount, $currency)
    {
        $getActualBalance = UserBalance::select()->where('email', '=', $toEmail)->first();
        $getActualBalance->$currency = Money::of($amount, $currency)->plus($amount)->getAmount();
        $getActualBalance->save();
    }

    public function addbalance(Request $request)
    {

        // Check the auth of User (Me)
        if (!empty(auth()->user()['email'])) {

            $userBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();

            $this->setBalanceAmount($request['currency'], $request['summa']);

            // If currency is not selected
            if (empty($request['currency'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Currency is not selected!',
                ]);
            } else {
                // Return the success message
                return response()->json([
                    'status' => 'success',
                    'message' => 'Balance ' . $request['summa'] . ' ' . $request['currency'] . ' is successfully added!',
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
                $this->removeBalanceAmount($request['currency'], $request['amount']);
                $this->setBalanceToTargetUser($request['toEmail'], $request['amount'], $request['currency']);

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
