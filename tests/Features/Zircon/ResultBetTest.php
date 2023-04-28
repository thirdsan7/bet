<?php

use Illuminate\Http\Client\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ResultBetTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=ResultBetSeeder');
    }

    public function test_resultBet_validData_expectedResponse()
    {
        $request = [
            'roundDetID' => 'running_bet',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 100
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'errorMessage' => 'Success',
            'data' => [
                'transactionId' => 'TransactionId',
                'balance' => 1000,
                'playerId' => 'PlayerId',
                'statementDate' => 'StatementDate',
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'data' => [
                    'roundDetID' => 'running_bet',
                    'gameID' => 1,
                    'balance' => 1000
                ]
            ]);
    }

    public function test_resultBet_incompleteRequest_expectedResponse()
    {
        $request = [
            'roundDetID' => 'roundDetID',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            //'turnover' => 100 // remove turnover
        ];

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => json_encode([
                        'turnover' => [
                            "The turnover field is required."
                        ]
                    ])
                ]
            ]);
    }

    public function test_resultBet_invalidGameID_expectedResponse()
    {
        $request = [
            'roundDetID' => 'roundDetID',
            'gameID' => 999, //invalid gameid
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 100
        ];

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => 'gameID not found'
                ]
            ]);
    }

    public function test_resultBet_invalidClientID_expectedResponse()
    {
        $request = [
            'roundDetID' => 'roundDetID',
            'gameID' => 1,
            'clientID' => 999,
            'totalWin' => 100,
            'turnover' => 100
        ];

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => 'ClientID not found'
                ]
            ]);
    }

    public function test_resultBet_invalidRoundDetID_expectedResponse()
    {
        $request = [
            'roundDetID' => 'invalid_rounddetid',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 100
        ];

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 103,
                    'message' => 'RoundDetID not found'
                ]
            ]);
    }

    public function test_resultBet_thirdPartyReturn409_expectedResponse()
    {
        $request = [
            'roundDetID' => 'third_party_error',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 100
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 409,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 102,
                    'message' => 'RoundDetID already settled'
                ]
            ]);
    }

    public function test_resultBet_thirdPartyReturn410_expectedResponse()
    {
        $request = [
            'roundDetID' => 'third_party_error',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 100
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 410,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 104,
                    'message' => 'RoundDetID already cancelled'
                ]
            ]);
    }

    public function test_resultBet_mockCWApi_expectedCWApiParams()
    {
        $request = [
            'roundDetID' => 'roundDetID_mockCWAPI',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 50
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

        $this->post('resultbet', $request);

        Http::assertSent(function (Request $lcRequest) {
            return
                $lcRequest->hasHeader('Authentication', env('LC_API_TOKEN')) &&
                $lcRequest->hasHeader('User-Agent', env('LC_API_USER_AGENT')) &&
                $lcRequest->hasHeader('X-Request-ID') &&
                $lcRequest->url() == env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . '/Bet/SettleBet' &&
                $lcRequest['BetResultReq']['WinAmount'] == 100 &&
                $lcRequest['BetResultReq']['Stake'] == 100 &&
                $lcRequest['BetResultReq']['EffectiveStake'] == 50 &&
                $lcRequest['BetResultReq']['PlayerId'] == 1 &&
                $lcRequest['BetResultReq']['GameCode'] == 1 &&
                $lcRequest['TransactionId'] == 'roundDetID_mockCWAPI-1-'.env('ENV_ID');
        });
    }
}