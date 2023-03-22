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
        Schema::create('gameloginsession', function (Blueprint $table) {
            $table->id('gameLoginSessionID');
            $table->bigInteger('getLoginInfoID');
            $table->string('token')->nullable();
            $table->integer('gameID');
            $table->timestamp('timestampCreated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gameloginsession');
    }
};
