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
        Schema::create('transactioncw', function (Blueprint $table) {
            $table->id('transactionCWID');
            $table->bigInteger('sboClientID');
            $table->string('roundDetID', 100)->nullable();
            $table->integer('gameID')->nullable();
            $table->string('sessionID', 40)->nullable();
            $table->string('refNo', 100)->nullable();
            $table->decimal('stake', 19, 6);
            $table->decimal('totalWin', 19, 6)->default(0);
            $table->string('event', 1);
            $table->text('remark')->nullable();
            $table->timestamp('timestampStart')->nullable();
            $table->timestamp('timestampEnd')->nullable();
            $table->timestamp('timestampUpdated')->nullable();

            $table->unique(['sboClientID', 'roundDetID', 'gameID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactioncw');
    }
};
