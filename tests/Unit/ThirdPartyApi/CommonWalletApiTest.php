<?php

use Tests\TestCase;
use App\Libraries\LaravelLib;
use App\Libraries\LaravelHttp;
use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\ThirdPartyApi\CommonWalletApi;
use App\ThirdPartyApi\Validators\ResponseValidator;
use Illuminate\Http\Client\Response;

class CommonWalletApiTest extends TestCase
{
    public function makeApi($http = null, $lib = null, $validator = null)
    {
        $http ??= $this->createStub(LaravelHttp::class);
        $lib ??= $this->createStub(LaravelLib::class);
        $validator ??= $this->createStub(ResponseValidator::class);

        return new CommonWalletApi($http, $lib, $validator);
    }

    public function test_placeBet_mockBet_getSessionID()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getSessionID');

        $api = $this->makeApi();
        $api->placeBet($mockBet);
    }

    public function test_placeBet_mockBet_getIp()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getIp');

        $api = $this->makeApi();
        $api->placeBet($mockBet);
    }

    public function test_placeBet_mockBet_getClientID()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getClientID');

        $api = $this->makeApi();
        $api->placeBet($mockBet);
    }

    public function test_placeBet_mockBet_getGameID()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getGameID');

        $api = $this->makeApi();
        $api->placeBet($mockBet);
    }

    public function test_placeBet_mockBet_getStake()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getStake');

        $api = $this->makeApi();
        $api->placeBet($mockBet);
    }

    public function test_placeBet_mockBet_getRefNo()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getRefNo');

        $api = $this->makeApi();
        $api->placeBet($mockBet);
    }

    public function test_placeBet_mockLib_randomString()
    {
        $bet = $this->createStub(IBet::class);

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('randomString')
            ->with(15);

        $api = $this->makeApi(null, $mockLib);
        $api->placeBet($bet);
    }

    public function test_placeBet_mockHttp_post()
    {

        $stubBet = $this->createStub(IBet::class);
        $stubBet->method('getSessionID')
            ->willReturn('sessionID');

        $stubBet->method('getClientID')
            ->willReturn(2);

        $stubBet->method('getGameID')
            ->willReturn(3);

        $stubBet->method('getIp')
            ->willReturn('Ip');

        $stubBet->method('getStake')
            ->willReturn(10.0);

        $stubBet->method('getRefNo')
            ->willReturn('refNo');

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $mockHttp = $this->createMock(LaravelHttp::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->with(
                env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . "/Bet/PlaceBet",
                [
                    'SessionId' => 'sessionID',
                    'PlayerIp' => 'Ip',
                    'PlayerId' => '2',
                    'Bet' => [
                        'GameCode' => '3',
                        'Stake' => 10.0,
                        'TransactionId' => 'refNo',
                    ]
                ],
                [
                    'Authentication' => env('LC_API_TOKEN'),
                    'User-Agent' => env('LC_API_USER_AGENT'),
                    'X-Request-ID' => 'randomString',
                ]
            );

        

        $api = $this->makeApi($mockHttp, $stubLib);
        $api->placeBet($stubBet);
    }

    public function test_placeBet_mockValidator_validate()
    {
        $bet = $this->createStub(IBet::class);

        $response = $this->createStub(Response::class);

        $mockValidator = $this->createMock(ResponseValidator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($response);

        $stubHttp = $this->createStub(LaravelHttp::class);
        $stubHttp->method('post')
            ->willReturn($response);

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $api = $this->makeApi($stubHttp, $stubLib, $mockValidator);
        $api->placeBet($bet);
    }

    public function test_settleBet_mockBet_getRefNo()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getRefNo');

        $api = $this->makeApi();
        $api->settleBet($mockBet);
    }

    public function test_settleBet_mockBet_getTotalWin()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getTotalWin');

        $api = $this->makeApi();
        $api->settleBet($mockBet);
    }

    public function test_settleBet_mockBet_getStake()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getStake');

        $api = $this->makeApi();
        $api->settleBet($mockBet);
    }

    public function test_settleBet_mockBet_getTurnover()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getTurnover');

        $api = $this->makeApi();
        $api->settleBet($mockBet);
    }

    public function test_settleBet_mockLib_randomString()
    {
        $bet = $this->createStub(IBet::class);

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('randomString')
            ->with(15);

        $api = $this->makeApi(null, $mockLib);
        $api->settleBet($bet);
    }

    public function test_settleBet_mockHttp_post()
    {

        $stubBet = $this->createStub(IBet::class);
        $stubBet->method('getRefNo')
            ->willReturn('refNo');

        $stubBet->method('getTotalWin')
            ->willReturn(20.0);

        $stubBet->method('getStake')
            ->willReturn(10.0);

        $stubBet->method('getTurnover')
            ->willReturn(5.0);

        $stubBet->method('getClientID')
            ->willReturn(1);

        $stubBet->method('getGameID')
            ->willReturn(2);

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $mockHttp = $this->createMock(LaravelHttp::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->with(
                env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . "/Bet/SettleBet",
                [
                    'TransactionId' => 'refNo',
                    'BetResultReq' => [
                        'WinAmount' => 20.0,
                        'Stake' => 10.0, 
                        'EffectiveStake' => 5.0,
                        'PlayerId' => 1,
                        'GameCode' => 2,
                    ]
                ],
                [
                    'Authentication' => env('LC_API_TOKEN'),
                    'User-Agent' => env('LC_API_USER_AGENT'),
                    'X-Request-ID' => 'randomString',
                ]
            );

        $api = $this->makeApi($mockHttp, $stubLib);
        $api->settleBet($stubBet);
    }

    public function test_settleBet_mockValidator_validate()
    {
        $bet = $this->createStub(IBet::class);

        $response = $this->createStub(Response::class);

        $mockValidator = $this->createMock(ResponseValidator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($response);

        $stubHttp = $this->createStub(LaravelHttp::class);
        $stubHttp->method('post')
            ->willReturn($response);

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $api = $this->makeApi($stubHttp, $stubLib, $mockValidator);
        $api->settleBet($bet);
    }

    public function test_checkBet_mockBet_getClientID()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getClientID');

        $api = $this->makeApi();
        $api->checkBet($mockBet);
    }

    public function test_checkBet_mockBet_getRefNo()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getRefNo');

        $api = $this->makeApi();
        $api->checkBet($mockBet);
    }

    public function test_checkBet_mockLib_randomString()
    {
        $bet = $this->createStub(IBet::class);

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('randomString')
            ->with(15);

        $api = $this->makeApi(null, $mockLib);
        $api->checkBet($bet);
    }

    public function test_checkBet_mockHttp_post()
    {

        $stubBet = $this->createStub(IBet::class);
        $stubBet->method('getRefNo')
            ->willReturn('refNo');

        $stubBet->method('getClientID')
            ->willReturn(1);

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $mockHttp = $this->createMock(LaravelHttp::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->with(
                env('LC_API_URL') . '/' . env('GAME_PROVIDER_NAME') . "/Bet/CheckBet",
                [
                    'TransactionId' => 'refNo',
                    'PlayerId' => 1
                ],
                [
                    'Authentication' => env('LC_API_TOKEN'),
                    'User-Agent' => env('LC_API_USER_AGENT'),
                    'X-Request-ID' => 'randomString',
                ]
            );

        $api = $this->makeApi($mockHttp, $stubLib);
        $api->checkBet($stubBet);
    }

    public function test_checkBet_mockValidator_validate()
    {
        $bet = $this->createStub(IBet::class);

        $response = $this->createStub(Response::class);

        $mockValidator = $this->createMock(ResponseValidator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($response);

        $stubHttp = $this->createStub(LaravelHttp::class);
        $stubHttp->method('post')
            ->willReturn($response);

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $api = $this->makeApi($stubHttp, $stubLib, $mockValidator);
        $api->checkBet($bet);
    }
}