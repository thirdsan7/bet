<?php
use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class BetCompleteTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=ResultBetSeeder');
    }
    
    public function test_settleBetComplete_WithoutActiveRoundTotalWinLCApiResponse1000_balance1000()
    {
        $request = [
            'uid' => 1,
            'round' => 'running_bet',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
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

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame('status=ok&bal=1000', $response->original);
    }

    public function test_settleBetComplete_incompleteRequest_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            // 'status' => 'complete' //incomplete requests
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(500);
    }

    public function test_settleBetComplete_invalidGameID_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'round' => 'round',
            'gameid' => 999, //invalid gameid
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame('status=invalid&error=3', $response->original);
    }

    public function test_settleBetComplete_invalidClientID_expectedResponse()
    {
        $request = [
            'uid' => 999, //invalid clientid
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(500);
    }

    public function test_settleBetComplete_invalidRoundDetID_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'round' => 'invalid_rounddetid', //invalid rounddetid
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(500);
    }

    public function test_settleBetComplete_thirdPartyReturn409_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'round' => 'third_party_error',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 409,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame('status=invalid&error=15', $response->original);
    }

    public function test_settleBetComplete_thirdPartyReturn410_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'round' => 'third_party_error',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 410,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame('status=invalid&error=16', $response->original);
    }

    public function test_sellBet_mockCWApi_expectedCWApiParams()
    {
        $request = [
            'uid' => 1,
            'round' => 'roundDetID_mockCWAPI',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
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

        $this->call('GET', '/api/eyecon', $request);

        Http::assertSent(function (Request $lcRequest) {
            return
                $lcRequest->hasHeader('Authentication', env('LC_API_TOKEN')) &&
                $lcRequest->hasHeader('User-Agent', env('LC_API_USER_AGENT')) &&
                $lcRequest->hasHeader('X-Request-ID') &&
                $lcRequest->url() == env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . '/Bet/SettleBet' &&
                $lcRequest['BetResultReq']['WinAmount'] == 100 &&
                $lcRequest['BetResultReq']['Stake'] == 100 &&
                $lcRequest['BetResultReq']['EffectiveStake'] == 100 &&
                $lcRequest['BetResultReq']['PlayerId'] == 1 &&
                $lcRequest['BetResultReq']['GameCode'] == 1 &&
                $lcRequest['TransactionId'] == 'roundDetID_mockCWAPI-1-'.env('ENV_ID');
        });
    }
}