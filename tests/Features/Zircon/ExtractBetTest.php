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

    public function test_extractBet_incompleteRequest_expectedResponse()
    {
        $request = [
            'roundDetID' => 'running_bet',
            'gameID' => 1,
            // 'clientID' => 1 //incomplete request
        ];

        $this->json('POST', 'extractbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => json_encode([
                        'clientID' => [
                            "The client i d field is required."
                        ]
                    ])
                ]
            ]);
    }

    public function test_extractBet_invalidGameID_expectedResponse()
    {
        $request = [
            'roundDetID' => 'running_bet',
            'gameID' => 999, //invalid gameid
            'clientID' => 1
        ];

        $this->json('POST', 'extractbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => 'gameID not found'
                ]
            ]);
    }

    public function test_extractBet_invalidClientID_expectedResponse()
    {
        $request = [
            'roundDetID' => 'running_bet',
            'gameID' => 1, 
            'clientID' => 999 //invalid clientid
        ];

        $this->json('POST', 'extractbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => 'ClientID not found'
                ]
            ]);
    }

    public function test_extractBet_invalidRoundDetID_expectedResponse()
    {
        $request = [
            'roundDetID' => 'invalid_rounddetid', // invalid rounddetid
            'gameID' => 1,
            'clientID' => 1
        ];

        $this->json('POST', 'extractbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 103,
                    'message' => 'RoundDetID not found'
                ]
            ]);
    }

    public function test_extractBet_mockCWApi_expectedCWApiParams()
    {
        $request = [
            'roundDetID' => 'roundDetID_mockCWAPI',
            'gameID' => 1,
            'clientID' => 1
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'errorMessage' => 'Success',
            'data' => [
                'balance' => 1000
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $this->post('extractbet', $request);

        Http::assertSent(function (Request $lcRequest) {
            return
                $lcRequest->hasHeader('Authentication', env('LC_API_TOKEN')) &&
                $lcRequest->hasHeader('User-Agent', env('LC_API_USER_AGENT')) &&
                $lcRequest->hasHeader('X-Request-ID') &&
                $lcRequest->url() == env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . '/Bet/CheckBet' &&
                $lcRequest['PlayerId'] == 1 &&
                $lcRequest['TransactionId'] == 'roundDetID_mockCWAPI-1-'.env('ENV_ID');
        });
    }
}