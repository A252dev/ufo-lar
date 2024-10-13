<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'AUD',
        'BRL',
        'CAD',
        'CNY',
        'CZK',
        'DKK',
        'EUR',
        'HKD',
        'HUF',
        'ILS',
        'JPY',
        'MYR',
        'MXN',
        'TWD',
        'NZD',
        'NOK',
        'PHP',
        'PLN',
        'GBP',
        'SGD',
        'SEK',
        'CHF',
        'THB',
        'USD',
    ];

    public function __construct()
    {
    }

    public function create($email, $AUD, $BRL, $CAD, $CNY, $CZK, $DKK, $EUR, $HKD, $HUF, $ILS, $JPY, $MYR, $MXN, $TWD, $NZD, $NOK, $PHP, $PLN, $GBP, $SGD, $SEK, $CHF, $THB, $USD)
    {
        $this->email = $email;
        $this->AUD = $AUD;
        $this->BRL = $BRL;
        $this->CAD = $CAD;
        $this->CNY = $CNY;
        $this->CZK = $CZK;
        $this->DKK = $DKK;
        $this->EUR = $EUR;
        $this->HKD = $HKD;
        $this->HUF = $HUF;
        $this->ILS = $ILS;
        $this->JPY = $JPY;
        $this->MYR = $MYR;
        $this->MXN = $MXN;
        $this->TWD = $TWD;
        $this->NZD = $NZD;
        $this->NOK = $NOK;
        $this->PHP = $PHP;
        $this->PLN = $PLN;
        $this->GBP = $GBP;
        $this->SGD = $SGD;
        $this->SEK = $SEK;
        $this->CHF = $CHF;
        $this->THB = $THB;
        $this->USD = $USD;
        
        return $this;
    }

}