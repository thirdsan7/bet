<?php

use Illuminate\Http\Client\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ExtractBetTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=ResultBetSeeder');
    }

    public function test_extractBet_validRequest_expectedResponse()
    {
        $request = [
            'roundDetID' => 'running_bet',
            'gameID' => 1,
            'clientID' => 1
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'errorMessage' => 'Success',
            'data' => [
                'transactionId' => 'running_bet-1-0',
                'stake' => 100,
                'winAmount' => 100,
                'status' => 'Running',
                'statementDate' => 'StatementDate',
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $this->json('POST', 'extractbet', $request)
            ->seeJson([
                'data' => [
                    'roundDetID' => 'running_bet',
                    'gameID' => 1,
                    'event' => 'R',
                    'stake' => 100
                ]
            ]);
    }
}