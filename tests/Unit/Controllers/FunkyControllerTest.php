<?php

use Tests\TestCase;
use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Responses\FunkyResponse;
use App\Validators\FunkyValidator;
use App\Http\Controllers\FunkyController;

class FunkyControllerTest extends TestCase
{
    public function makeController($validator = null, $service = null, $response = null)
    {
        $validator ??= $this->createStub(FunkyValidator::class);
        $service ??= $this->createStub(BetService::class);
        $response ??= $this->createStub(FunkyResponse::class);

        return new FunkyController($validator, $service, $response);
    }

    public function test_placeBet_mockLib_validateSellBet()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockLib = $this->createMock(FunkyValidator::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, [
                'bet.gameCode' => 'required',
                'bet.refNo' => 'required',
                'bet.stake' => 'required',
                'sessionId' => 'required',
                'playerIp' => 'required'
            ]);

        $controller = $this->makeController($mockLib);
        $controller->placeBet($request, $game, $player, $bet);
    }

    public function test_placeBet_mockGame_initByGameID()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(1);

        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->placeBet($request, $mockGame, $player, $bet);
    }

    public function test_placeBet_mockPlayer_initByGameID()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $game = $this->createStub(CasinoGame::class);

        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initBySessionIDGameID')
            ->with('sessionId', $game);
        
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->placeBet($request, $game, $mockPlayer, $bet);
    }

    public function test_placeBet_mockBet_new()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('new')
            ->with($player, $game, 'refNo');

        $controller = $this->makeController();
        $controller->placeBet($request, $game, $player, $mockBet);
    }

    public function test_placeBet_mockBet_setStake()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setStake')
            ->with(10);

        $controller = $this->makeController();
        $controller->placeBet($request, $game, $player, $mockBet);
    }

    public function test_placeBet_mockBet_setIp()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setIp')
            ->with('playerIp');

        $controller = $this->makeController();
        $controller->placeBet($request, $game, $player, $mockBet);
    }

    public function test_placeBet_mockService_startBet()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $bet = $this->createStub(ZirconBet::class);
        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);

        $mockService = $this->createMock(BetService::class);
        $mockService->expects($this->once())
            ->method('startBet')
            ->with($player, $game, $bet);

        $controller = $this->makeController(null, $mockService);
        $controller->placeBet($request, $game, $player, $bet);
    }

    public function test_placeBet_mockResponse_placeBet()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 'refNo',
                'stake' => 10,
            ],
            'sessionId' => 'sessionId',
            'playerIp' => 'playerIp'
        ]);

        $bet = $this->createStub(ZirconBet::class);
        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);

        $mockResponse = $this->createMock(FunkyResponse::class);
        $mockResponse->expects($this->once())
            ->method('placeBet')
            ->with($player);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->placeBet($request, $game, $player, $bet);
    }

    public function test_settleBet_mockValidator_validate()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockValidator = $this->createMock(FunkyValidator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($request, [
                'refNo' => 'required',
                'betResultReq.winAmount' => 'required',
                'betResultReq.stake' => 'required',
                'betResultReq.effectiveStake' => 'required',
                'betResultReq.playerId' => 'required',
                'betResultReq.gameCode' => 'required'
            ]);

        $controller = $this->makeController($mockValidator);
        $controller->settleBet($request, $game, $player, $bet);
    }

    public function test_settleBet_mockGame_initByGameID()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(1);

        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->settleBet($request, $mockGame, $player, $bet);
    }

    public function test_settleBet_mockPlayer_initByClientID()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initByClientID')
            ->with(2);

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->settleBet($request, $game, $mockPlayer, $bet);
    }

    public function test_settleBet_mockBet_initByGamePlayerRoundDetID()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('initByGamePlayerRoundDetID')
            ->with($game, $player, 'refNo');

        $controller = $this->makeController();
        $controller->settleBet($request, $game, $player, $mockBet);
    }

    public function test_settleBet_mockBet_setTotalWin()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setTotalWin')
            ->with(10.0);

        $controller = $this->makeController();
        $controller->settleBet($request, $game, $player, $mockBet);
    }

    public function test_settleBet_mockBet_setTurnover()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setTurnover')
            ->with(5.0);

        $controller = $this->makeController();
        $controller->settleBet($request, $game, $player, $mockBet);
    }

    public function test_settleBet_mockResponse_settleBet()
    {
        $request = new Request([
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 2,
                'winAmount' => 10.0,
                'effectiveStake' => 5.0
            ],
            'refNo' => 'refNo'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockResponse = $this->createMock(FunkyResponse::class);
        $mockResponse->expects($this->once())
            ->method('settleBet')
            ->with($player, $bet);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->settleBet($request, $game, $player, $bet);
    }
}