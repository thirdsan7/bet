<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResultBetSeeder extends Seeder
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
            ]
        ]);

        DB::table('transactioncw')->insert([
            'running bet' => [
                'sboClientID' => 1,
                'roundDetID' => 'running_bet',
                'gameID' => 1,
                'sessionID' => 1,
                'refNo' => 1,
                'stake' => 100,
                'event' => 'R',
                'timestampStart' => Carbon::now()->format('Y-m-d H:i:s.u'),
                'timestampEnd' => Carbon::now()->format('Y-m-d H:i:s.u'),
            ]
        ]);
    }
}
