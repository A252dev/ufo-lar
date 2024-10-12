<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();
            
            // Email using like a unique ID
            $table->string('email');
            $table->double('AUD'); // Australian dollar
            $table->double('BRL'); // Brazilian real
            $table->double('CAD'); // Canadian dollar
            $table->double('CNY'); // Chinese Renmenbi
            $table->double('CZK'); // Czech koruna
            $table->double('DKK'); // Danish krone
            $table->double('EUR'); // Euro
            $table->double('HKD'); // Hong Kong dollar
            $table->double('HUF'); // Hungarian forint
            $table->double('ILS'); // Israeli new shekel
            $table->double('JPY'); // Japanese yen
            $table->double('MYR'); // Malaysian ringgit
            $table->double('MXN'); // Mexican peso
            $table->double('TWD'); // New Taiwan dollar
            $table->double('NZD'); // New Zealand dollar
            $table->double('NOK'); // Norwegian krone
            $table->double('PHP'); // Philippine peso
            $table->double('PLN'); // Polish złoty
            $table->double('GBP'); // Pound sterling
            $table->double('SGD'); // Singapore dollar
            $table->double('SEK'); // Swedish krona
            $table->double('CHF'); // Swiss franc
            $table->double('THB'); // Thai baht
            $table->double('USD'); // United States dollar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_balances');
    }
};
