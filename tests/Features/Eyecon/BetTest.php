<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class BetTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=SellBetSeeder');
    }
    public function test_bet_lcApiResponse1000_expectedResponse()
    {
        $request = [
            'uid' => 1, 
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 20,
            'gameid' => 1,
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
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

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=ok&bal=1000');
    }

    public function test_bet_incompleteRequest_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 20,
            'gameid' => 1,
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            // 'jpwin' => 'jpwin' //remove jpwin
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(500);
    }

    public function test_bet_invalidAccessID_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'invalid_accessid', //invalid accessid
            'type' => 'BET',
            'round' => 20,
            'gameid' => 1,
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(500);
    }

    public function test_bet_invalidGameID_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 20,
            'gameid' => 10, //invalid gameid
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=3');
    }

    public function test_bet_invalidGameSession_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1, // sessionid with gameid 1
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 20,
            'gameid' => 2, //different gameid for sessionid 1
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=12');
    }

    public function test_bet_invalidGameUnderMaintenance_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 2, // sessionid with gameid 4
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 20,
            'gameid' => 4, //gameid under maintenance
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(503);
    }

    public function test_bet_betAlreadyExists_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_already_exists', //roundDetID already exists
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=15');
    }

    public function test_bet_thirdPartyReturn401_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 401,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=12');
    }

    public function test_bet_thirdPartyReturn402_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 402,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=13');
    }

    public function test_bet_thirdPartyReturn403_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 403,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=15');
    }

    public function test_bet_thirdPartyReturn405_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 405,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $response->assertStatus(503);
    }

    public function test_bet_thirdPartyReturn406_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 406,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=21');
    }

    public function test_bet_thirdPartyReturn407_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 407,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=21');
    }

    public function test_bet_thirdPartyReturn409_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 409,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=15');
    }

    public function test_bet_thirdPartyReturn410_expectedResponse()
    {
        $request = [
            'uid' => 1,
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'roundDetID_third_party',
            'gameid' => 1, 
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 410,
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame($response->original, 'status=invalid&error=16');
    }
}