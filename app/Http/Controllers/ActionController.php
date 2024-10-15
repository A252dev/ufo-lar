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

    // Add the My balance
    public function addBalanceAmount($currency, $amount)
    {
        $getActualBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();
        $getActualBalance[$currency] = Money::of($getActualBalance[$currency], $currency)->plus($amount)->getAmount();
        $getActualBalance->save();
    }

    // Remove the My balance
    public function removeBalanceAmount($currency, $amount)
    {
        $getActualBalance = UserBalance::select()->where('email', '=', auth()->user()['email'])->first();
        // dd($getActualBalance[$currency]);
        // Check user balance on zero
        if ($getActualBalance[$currency] <= 0 || $amount > $getActualBalance[$currency]) {
            return null;
        } else {
            $getActualBalance[$currency] = Money::of($getActualBalance[$currency], $currency)->minus($amount)->getAmount();
            $getActualBalance->save();
        }
    }

    // Set Balance to current User
    public function setBalanceToTargetUser($toEmail, $amount, $currency)
    {
        $getActualBalance = UserBalance::select()->where('email', '=', $toEmail)->first();
        // If target user not found
        if (empty($getActualBalance)) {
            return null;
        } else {
            $getActualBalance[$currency] = Money::of($getActualBalance[$currency], $currency)->plus($amount)->getAmount();
            $getActualBalance->save();
        }
    }

    public function addbalance(Request $request)
    {

        // Check the auth of User (Me)
        if (!empty(auth()->user()['email'])) {

            // Add the balance
            $this->addBalanceAmount($request['currency'], $request['amount']);

            // Return the success message
            return response()->json([
                'status' => 'success',
                'message' => 'Balance ' . $request['amount'] . ' ' . $request['currency'] . ' is successfully added!',
            ]);

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

            // Validate the data
            $validate = Validator::make($request->all(), [
                'toEmail' => 'required|email',
                'amount' => 'required',
                'currency' => 'required',
            ]);

            // If data is invalid, return an error
            if ($validate->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid data',
                ]);
            }

            // Transfer money with currect currency
            $this->removeBalanceAmount($request['currency'], $request['amount']);
            $this->setBalanceToTargetUser($request['toEmail'], $request['amount'], $request['currency']);

            // Return the success message
            return response()->json([
                'status' => 'success',
                'message' => 'Transfer from ' . auth()->user()['email'] . ' to ' . $request['toEmail'] . ' in ' . $request['amount'] . ' ' . $request['currency'] . ' is completed!',
            ]);

        } else {

            // Return message that user is not authorized
            return response()->json([
                'status' => 'error',
                'message' => 'User is not authorized!',
            ]);
        }

    }

    public function convert(Request $request)
    {
        // Remove the balance from my account
        $this->removeBalanceAmount($request['fromCurrency'], $request['amount']);

        // Set the converted balance
        $this->addBalanceAmount(
            $request['toCurrency'],
            $this->getConvertedBalance($request['fromCurrency'], $request['toCurrency'], $request['amount'])
        );

        // Return the success message
        return response()->json([
            'status' => 'success',
            'message' => 'Currency from ' . $request['fromCurrency'] . ' to ' . $request['toCurrency'] . ' in ' . $request['amount'] . ' is successfully converted!',
        ]);
    }

    public function getConvertedBalance($fromCurrency, $toCurrency, $amount)
    {
        $getActualData = file_get_contents("https://v6.exchangerate-api.com/v6/ce3ba77a89e6e5e13dcbaf76/latest/" . $fromCurrency);
        $actualBalance = json_decode($getActualData, true)['conversion_rates'][$toCurrency];
        return Money::of(round($actualBalance, 2), $toCurrency)->multipliedBy($amount);
    }

}
