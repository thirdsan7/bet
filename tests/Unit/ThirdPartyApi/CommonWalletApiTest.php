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

    public function test_placeBet_mockPlayer_getSessionID()
    {
        $mockPlayer = $this->createMock(IPlayer::class);
        $mockPlayer->expects($this->once())
            ->method('getSessionID')
            ->willReturn('sessionID');

        $stubGame = $this->createStub(IGame::class);
        $stubBet = $this->createStub(IBet::class);

        $api = $this->makeApi();
        $api->placeBet($mockPlayer, $stubGame, $stubBet);
    }

    public function test_placeBet_mockBet_getIp()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getIp')
            ->willReturn('ip');

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubGame = $this->createStub(IGame::class);

        $api = $this->makeApi();
        $api->placeBet($stubPlayer, $stubGame, $mockBet);
    }

    public function test_placeBet_mockBet_getClientID()
    {
        $mockPlayer = $this->createMock(IPlayer::class);
        $mockPlayer->expects($this->once())
            ->method('getClientID')
            ->willReturn(1);

        $stubGame = $this->createStub(IGame::class);
        $stubBet = $this->createStub(IBet::class);

        $api = $this->makeApi();
        $api->placeBet($mockPlayer, $stubGame, $stubBet);
    }

    public function test_placeBet_mockBet_getGameID()
    {
        $mockGame = $this->createMock(IGame::class);
        $mockGame->expects($this->once())
            ->method('getGameID')
            ->willReturn(1);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubBet = $this->createStub(IBet::class);

        $api = $this->makeApi();
        $api->placeBet($stubPlayer, $mockGame, $stubBet);
    }

    public function test_placeBet_mockBet_getStake()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getStake')
            ->willReturn(1.0);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubGame = $this->createStub(IGame::class);

        $api = $this->makeApi();
        $api->placeBet($stubPlayer, $stubGame, $mockBet);
    }

    public function test_placeBet_mockBet_getRefNo()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('getRefNo')
            ->willReturn('refno');

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubGame = $this->createStub(IGame::class);

        $api = $this->makeApi();
        $api->placeBet($stubPlayer, $stubGame, $mockBet);
    }

    public function test_placeBet_mockLib_randomString()
    {
        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('randomString')
            ->with(15);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubGame = $this->createStub(IGame::class);
        $stubBet = $this->createStub(IBet::class);

        $api = $this->makeApi(null, $mockLib);
        $api->placeBet($stubPlayer, $stubGame, $stubBet);
    }

    public function test_placeBet_mockHttp_post()
    {

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

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('getSessionID')
            ->willReturn('sessionID');

        $stubPlayer->method('getClientID')
            ->willReturn(2);

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('getGameID')
            ->willReturn(3);

        $stubBet = $this->createStub(IBet::class);
        $stubBet->method('getIp')
            ->willReturn('Ip');

        $stubBet->method('getStake')
            ->willReturn(10.0);

        $stubBet->method('getRefNo')
            ->willReturn('refNo');

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $api = $this->makeApi($mockHttp, $stubLib);
        $api->placeBet($stubPlayer, $stubGame, $stubBet);
    }

    public function test_placeBet_mockValidator_validate()
    {
        $response = $this->createStub(Response::class);

        $mockValidator = $this->createMock(ResponseValidator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($response);

        $stubHttp = $this->createStub(LaravelHttp::class);
        $stubHttp->method('post')
            ->willReturn($response);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('getSessionID')
            ->willReturn('sessionID');

        $stubPlayer->method('getClientID')
            ->willReturn(2);

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('getGameID')
            ->willReturn(3);

        $stubBet = $this->createStub(IBet::class);
        $stubBet->method('getIp')
            ->willReturn('Ip');

        $stubBet->method('getStake')
            ->willReturn(10.0);

        $stubBet->method('getRefNo')
            ->willReturn('refNo');

        $stubLib = $this->createStub(LaravelLib::class);
        $stubLib->method('randomString')
            ->willReturn('randomString');

        $api = $this->makeApi($stubHttp, $stubLib, $mockValidator);
        $api->placeBet($stubPlayer, $stubGame, $stubBet);
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
}