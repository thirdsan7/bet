<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PlaceBetTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=SellBetSeeder');
    }
    public function test_placeBet_validData_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'errorMessage' => 'Success',
            'data' => [
                'balance' => 1000   
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 0,
                'errorMessage' => 'NoError',    
                'data' => [
                    'balance' => 1000
                ]
            ]);
    }

    public function test_placeBet_validDataWrongAuthenticationToken_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'errorMessage' => 'Success',
            'data' => [
                'balance' => 1000   
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => 'invalid_authentication_token'
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 400,
                'errorMessage' => 'Invalid input'
            ]);
    }

    public function test_placeBet_incompleteRequest_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 1,
            // 'playerIp' => 'playerIp' //playerIp removed
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 400,
                'errorMessage' => 'Invalid input'
            ]);
    }

    public function test_placeBet_invalidGameID_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 10, //invalid game id
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 400,
                'errorMessage' => 'Invalid input'
            ]);
    }

    public function test_placeBet_invalidGameSession_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 2, // different gameid for sessionid 1
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 1, // sessionid with gameid 1
            'playerIp' => 'playerIp'
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 401,
                'errorMessage' => 'Player is not login'
            ]);
    }

    public function test_placeBet_gameUnderMaintenanceRealPlayer_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 4, // game under maintenance
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 2, // sessionid with gameid 4
            'playerIp' => 'playerIp'
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 405,
                'errorMessage' => 'API suspended'
            ]);
    }

    public function test_placeBet_betAlreadyExists_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1, 
                'refNo' => 'roundDetID_already_exists', //roundDetID already used
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 403,
                'errorMessage' => 'Bet already exists'
            ]);
    }

    public function test_placeBet_thirdPartyReturn401_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 401
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 401,
                'errorMessage' => 'Player is not login'
            ]);
    }

    public function test_placeBet_thirdPartyReturn402_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 402
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 402,
                'errorMessage' => 'Insufficient balance'
            ]);
    }

    public function test_placeBet_thirdPartyReturn403_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 403
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 403,
                'errorMessage' => 'Bet already exists'
            ]);
    }

    public function test_placeBet_thirdPartyReturn405_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 405
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 405,
                'errorMessage' => 'API suspended'
            ]);
    }

    public function test_placeBet_thirdPartyReturn406_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 406
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 406,
                'errorMessage' => 'Over the max winning'
            ]);
    }

    public function test_placeBet_thirdPartyReturn407_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 407
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 407,
                'errorMessage' => 'Over the max loss'
            ]);
    }

    public function test_placeBet_thirdPartyReturn409_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 409
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 409,
                'errorMessage' => 'Bet was already settled'
            ]);
    }

    public function test_placeBet_thirdPartyReturn410_expectedResponse()
    {
        $request = [
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'roundDetID_third_party',
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 410
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/PlaceBet', $request, $headers)
            ->seeJson([
                'errorCode' => 410,
                'errorMessage' => 'Bet was already cancelled'
            ]);
    }
}