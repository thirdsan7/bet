<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game')->insertOrIgnore([
            'zircon game' => [
                'gameID' => 1,
                'gameProviderID' => 1,
                'gameName' => Str::random(10),
                'isTestModeEnabled' => 0,
                'url' => Str::random(10),
            ],
            'funky game' => [
                'gameID' => 2,
                'gameProviderID' => 2,
                'gameName' => Str::random(10),
                'isTestModeEnabled' => 0,
                'url' => Str::random(10),
            ],
            'eyecon game' => [
                'gameID' => 3,
                'gameProviderID' => 3,
                'gameName' => Str::random(10),
                'isTestModeEnabled' => 0,
                'url' => Str::random(10),
            ],
            'game under maintenance' => [
                'gameID' => 4,
                'gameProviderID' => 1,
                'gameName' => Str::random(10),
                'isTestModeEnabled' => 1,
                'url' => Str::random(10),
            ],
        ]);
    }
}
