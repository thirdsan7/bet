<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('getlogininfo', function (Blueprint $table) {
            $table->id('getLoginInfoID');
            $table->bigInteger('sboClientID');
            $table->string('sessionID', 40);
            // $table->string('accountID', 50)->nullable();
            // $table->string('clientStatus', 20)->nullable();
            // $table->string('currencyCode', 3)->nullable();
            // $table->string('languageCode', 3)->nullable();
            $table->string('loginIP', 20)->nullable();
            $table->timestamp('timestampCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('getlogininfo');
    }
};
