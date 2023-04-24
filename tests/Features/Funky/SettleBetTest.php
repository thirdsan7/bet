<?php
use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class SettleBetTest extends TestCase
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
            'refNo' => 'running_bet',
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
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

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 0,
                'errorMessage' => 'NoError',    
                'data' => [
                    'refNo' => 'running_bet',
                    'balance' => 1000,
                    'playerId' => 1,
                    'currency' => '',
                    'statementDate' => 'StatementDate'
                ]
            ]);
    }
    
    public function test_resultBet_incompleteRequest_expectedResponse()
    {
        $request = [
            'refNo' => 'running_bet',
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                // 'stake' => 100 //incomplete request
            ]
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 400,
                'errorMessage' => 'Invalid input'
            ]);
    }

    public function test_resultBet_invalidGameID_expectedResponse()
    {
        $request = [
            'refNo' => 'running_bet',
            'betResultReq' => [
                'gameCode' => 999, //invalid gameID
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 400,
                'errorMessage' => 'Invalid input'
            ]);
    }

    public function test_resultBet_invalidClientID_expectedResponse()
    {
        $request = [
            'refNo' => 'running_bet',
            'betResultReq' => [
                'gameCode' => 1, 
                'playerId' => 999, //invalid clientID
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 400,
                'errorMessage' => 'Invalid input'
            ]);
    }

    public function test_resultBet_invalidRoundDetID_expectedResponse()
    {
        $request = [
            'refNo' => 'invalid_round_det_id',
            'betResultReq' => [
                'gameCode' => 1, 
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
        ];

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 404,
                'errorMessage' => 'Bet was not found'
            ]);
    }

    public function test_resultBet_thirdPartyReturn409_expectedResponse()
    {
        $request = [
            'refNo' => 'roundDetID_mockCWAPI',
            'betResultReq' => [
                'gameCode' => 1, 
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 409,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 409,
                'errorMessage' => 'Bet was already settled'
            ]);
    }

    public function test_resultBet_thirdPartyReturn410_expectedResponse()
    {
        $request = [
            'refNo' => 'roundDetID_mockCWAPI',
            'betResultReq' => [
                'gameCode' => 1, 
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 410,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 410,
                'errorMessage' => 'Bet was already cancelled'
            ]);
    }

    public function test_sellBet_mockCWApi_expectedCWApiParams()
    {
        $request = [
            'refNo' => 'roundDetID_mockCWAPI',
            'betResultReq' => [
                'gameCode' => 1, 
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
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

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->post('/Funky/Bet/SettleBet', $request, $headers);

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