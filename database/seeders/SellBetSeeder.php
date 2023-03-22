<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SellBetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('getlogininfo')->insert([
            'valid request' => [
                'getLoginInfoID' => 1,
                'sboClientID' => 1,
                'sessionID' => 1,
                'loginIP' => 'loginIP',
                'timestampCreated' => Carbon::now()->format('Y-m-d H:i:s.u')
            ],
            'game under maintenance' => [
                'getLoginInfoID' => 2,
                'sboClientID' => 2,
                'sessionID' => 2,
                'loginIP' => 'loginIP',
                'timestampCreated' => Carbon::now()->format('Y-m-d H:i:s.u')
            ]
        ]);

        DB::table('gameloginsession')->insert([
            'valid request' => [
                'getLoginInfoID' => 1,
                'token' => 'test_token_sellbet',
                'gameID' => 1,
            ],
            'game under maintenance' => [
                'getLoginInfoID' => 2,
                'token' => 'test_token_sellbet_maintenance',
                'gameID' => 4,
            ]
        ]);

        DB::table('transactioncw')->insert([
            'bet already exists' => [
                'sboClientID' => 1,
                'roundDetID' => 'roundDetID_already_exists',
                'gameID' => 1,
                'sessionID' => 1,
                'refNo' => 1,
                'stake' => 10,
                'event' => 'L',
                'timestampStart' => Carbon::now()->format('Y-m-d H:i:s.u'),
                'timestampEnd' => Carbon::now()->format('Y-m-d H:i:s.u'),
            ]
        ]);
    }
}
