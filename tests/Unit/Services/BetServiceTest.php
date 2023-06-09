<?php

use Tests\TestCase;
use App\Services\BetService;
use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Exceptions\Game\SystemUnderMaintenanceException;
use App\ThirdPartyApi\CommonWalletApi;

class BetServiceTest extends TestCase
{
    public function makeService($api = null)
    {
        $api ??= $this->createStub(CommonWalletApi::class);

        return new BetService($api);
    }

    public function test_placeBet_mockGame_isUnderMaintenance()
    {
        $mockGame = $this->createMock(IGame::class);
        $mockGame->expects($this->once())
            ->method('isUnderMaintenance')
            ->willReturn(false);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('isTestPlayer')
            ->willReturn(true);

        $stubBet = $this->createStub(IBet::class);
        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 1
            ]);

        $service = $this->makeService($stubApi);
        $service->startBet($stubPlayer, $mockGame, $stubBet);
    }

    public function test_placeBet_mockPlayer_isTestPlayer()
    {
        $mockPlayer = $this->createMock(IPlayer::class);
        $mockPlayer->expects($this->once())
            ->method('isTestPlayer')
            ->willReturn(true);

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('isUnderMaintenance')
            ->willReturn(true);

        $stubBet = $this->createStub(IBet::class);
        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 1
            ]);

        $service = $this->makeService($stubApi);
        $service->startBet($mockPlayer, $stubGame, $stubBet);
    }

    public function test_placeBet_GameUnderMaintenancePlayerNotTestPlayer_SystemUnderMaintenanceException()
    {
        $this->expectException(SystemUnderMaintenanceException::class);

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('isUnderMaintenance')
            ->willReturn(true);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('isTestPlayer')
            ->willReturn(false);

        $stubBet = $this->createStub(IBet::class);
        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 1
            ]);

        $service = $this->makeService($stubApi);
        $service->startBet($stubPlayer, $stubGame, $stubBet);
    }

    public function test_placeBet_GameInMaintenanceFalseMockBet_create()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('create');

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('isUnderMaintenance')
            ->willReturn(false);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('isTestPlayer')
            ->willReturn(true);
        
        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 1
            ]);

        $service = $this->makeService($stubApi);
        $service->startBet($stubPlayer, $stubGame, $mockBet);
    }

    public function test_placeBet_GameInMaintenanceFalseMockApi_placeBet()
    {
        $bet = $this->createStub(IBet::class);

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('isUnderMaintenance')
            ->willReturn(false);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('isTestPlayer')
            ->willReturn(true);

        $mockApi = $this->createMock(CommonWalletApi::class);
        $mockApi->expects($this->once())
            ->method('placeBet')
            ->with($bet);

        $mockApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 1
            ]);

        $service = $this->makeService($mockApi);
        $service->startBet($stubPlayer, $stubGame, $bet);
    }

    public function test_placeBet_GameInMaintenanceFalseMockApi_getResponse()
    {
        $stubBet = $this->createStub(IBet::class);

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('isUnderMaintenance')
            ->willReturn(false);

        $stubPlayer = $this->createStub(IPlayer::class);
        $stubPlayer->method('isTestPlayer')
            ->willReturn(true);

        $mockApi = $this->createMock(CommonWalletApi::class);
        $mockApi->expects($this->once())
            ->method('getResponse')
            ->willReturn((object)[
                'balance' => 1
            ]);

        $service = $this->makeService($mockApi);
        $service->startBet($stubPlayer, $stubGame, $stubBet);
    }

    public function test_settleBet_mockBet_settle()
    {
        $player = $this->createStub(IPlayer::class);

        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('settle');

        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 10.0,
                'statementDate' => 'statementDate'
            ]);

        $service = $this->makeService($stubApi);
        $service->settleBet($player, $mockBet);

    }

    public function test_settleBet_mockApi_settleBet()
    {
        $player = $this->createStub(IPlayer::class);
        $bet = $this->createStub(IBet::class);

        $mockApi = $this->createMock(CommonWalletApi::class);
        $mockApi->expects($this->once())
            ->method('settleBet')
            ->with($bet);

        $mockApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 10.0,
                'statementDate' => 'statementDate'
            ]);

        $service = $this->makeService($mockApi);
        $service->settleBet($player, $bet);
    }

    public function test_settleBet_mockApi_getResponse()
    {
        $player = $this->createStub(IPlayer::class);
        $bet = $this->createStub(IBet::class);

        $mockApi = $this->createMock(CommonWalletApi::class);
        $mockApi->expects($this->once())
            ->method('getResponse')
            ->willReturn((object)[
                'balance' => 10.0,
                'statementDate' => 'statementDate'
            ]);

        $service = $this->makeService($mockApi);
        $service->settleBet($player, $bet);
    }

    public function test_settleBet_mockPlayer_setBalance()
    {
        $mockPlayer = $this->createMock(IPlayer::class);
        $mockPlayer->expects($this->once())
            ->method('setBalance')
            ->with(10.0);

        $bet = $this->createStub(IBet::class);

        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 10.0,
                'statementDate' => 'statementDate'
            ]);

        $service = $this->makeService($stubApi);
        $service->settleBet($mockPlayer, $bet);
    }

    public function test_settleBet_mockBet_setStatementDate()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('setStatementDate')
            ->with('statementDate');

        $player = $this->createStub(IPlayer::class);

        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'balance' => 10.0,
                'statementDate' => 'statementDate'
            ]);

        $service = $this->makeService($stubApi);
        $service->settleBet($player, $mockBet);
    }

    public function test_checkBet_mockApi_checkBet()
    {
        $bet = $this->createStub(IBet::class);

        $mockApi = $this->createMock(CommonWalletApi::class);
        $mockApi->expects($this->once())
            ->method('checkBet')
            ->with($bet);

        $mockApi->method('getResponse')
            ->willReturn((object)[
                'status' => 'status',
                'stake' => 10,
            ]);

        $service = $this->makeService($mockApi);
        $service->checkBet($bet);
    }

    public function test_checkBet_mockApi_getResponse()
    {
        $bet = $this->createStub(IBet::class);

        $mockApi = $this->createMock(CommonWalletApi::class);
        $mockApi->expects($this->once())
            ->method('getResponse')
            ->willReturn((object)[
                'status' => 'status',
                'stake' => 10,
            ]);

        $service = $this->makeService($mockApi);
        $service->checkBet($bet);
    }

    public function test_checkBet_mockBet_setStatus()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('setStatus')
            ->with('status');

        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'status' => 'status',
                'stake' => 10,
            ]);

        $service = $this->makeService($stubApi);
        $service->checkBet($mockBet);
    }

    public function test_checkBet_mockBet_setStake()
    {
        $mockBet = $this->createMock(IBet::class);
        $mockBet->expects($this->once())
            ->method('setStake')
            ->with(10);

        $stubApi = $this->createStub(CommonWalletApi::class);
        $stubApi->method('getResponse')
            ->willReturn((object)[
                'status' => 'status',
                'stake' => 10,
            ]);

        $service = $this->makeService($stubApi);
        $service->checkBet($mockBet);
    }
}