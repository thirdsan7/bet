<?php

use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class SellBetTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=SellBetSeeder');
    }

    public function test_sellBet_validData_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'data' => [
                'balance' => 1000
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 0,
                    'message' => 'Success'
                ],
                'data' => [
                    'roundDetID' => 'roundDetID',
                    'gameID' => 1,
                    'event' => 'R',
                    'balance' => 1000
                ]
            ]);
    }

    public function test_sellBet_incompleteRequest_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            // 'ip' => 'ip' // remove ip from request
        ];

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => json_encode([
                        'ip' => [
                            "The ip field is required."
                        ]
                    ])
                ]
            ]);
    }

    public function test_sellBet_invalidGameID_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID',
            'roundID' => 'roundID',
            'gameID' => 10, //invalid game id
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => -1,
                    'message' => 'Invalid data given',
                    'details' => 'gameID not found'
                ]
            ]);
    }

    public function test_sellBet_invalidGameSession_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID',
            'roundID' => 'roundID',
            'gameID' => 2, // different gameid for sessionid 1
            'clientID' => 1,
            'sessionID' => 1, // sessionid with gameid 1
            'ip' => 'ip'
        ];

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 1,
                    'message' => 'Session expired'
                ]
            ]);
    }

    public function test_sellBet_gameUnderMaintenanceRealPlayer_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID',
            'roundID' => 'roundID',
            'gameID' => 4, // game under maintenance
            'clientID' => 1,
            'sessionID' => 2, // sessionid with gameid 4
            'ip' => 'ip'
        ];

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 201,
                    'message' => 'System under maintenance',
                ]
            ]);
    }

    public function test_sellBet_betAlreadyExists_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_already_exists', //roundDetID already exists
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 101,
                    'message' => 'RoundDetID already used'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn401_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 401,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 1,
                    'message' => 'Session expired'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn402_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 402,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 4,
                    'message' => 'Not enough balance'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn403_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 403,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 101,
                    'message' => 'RoundDetID already used'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn405_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 405,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 201,
                    'message' => 'System under maintenance'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn406_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 406,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 5,
                    'message' => 'Betting limit exceed'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn407_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 407,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 5,
                    'message' => 'Betting limit exceed'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn409_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 409,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 102,
                    'message' => 'RoundDetID already settled'
                ]
            ]);
    }

    public function test_sellBet_thirdPartyReturn410_expectedResponse()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_third_party',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 410,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $this->json('POST', 'sellbet', $request)
            ->seeJson([
                'error' => [
                    'code' => 104,
                    'message' => 'TransactionDetID already cancelled'
                ]
            ]);
    }

    public function test_sellBet_mockCWApi_expectedCWApiParams()
    {
        $request = [
            'stake' => 100,
            'roundDetID' => 'roundDetID_mockCWAPI',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
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

        $this->post('sellbet', $request);

        Http::assertSent(function (Request $lcRequest) {
            return
                $lcRequest->hasHeader('Authentication', env('LC_API_TOKEN')) &&
                $lcRequest->hasHeader('User-Agent', env('LC_API_USER_AGENT')) &&
                $lcRequest->url() == env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . '/Bet/PlaceBet' &&
                $lcRequest['Bet']['GameCode'] == 1 &&
                $lcRequest['Bet']['Stake'] == 100 &&
                $lcRequest['Bet']['TransactionId'] == 'roundDetID_mockCWAPI-1-'.env('ENV_ID') &&
                $lcRequest['SessionId'] == 1 &&
                $lcRequest['PlayerIp'] == 'ip' &&
                $lcRequest['PlayerId'] == 1;
        });
    }
}