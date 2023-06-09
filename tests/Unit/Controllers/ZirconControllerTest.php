<?php

use Tests\TestCase;
use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Validators\Validator;
use App\Responses\ZirconResponse;
use App\Http\Controllers\ZirconController;

class ZirconControllerTest extends TestCase
{
    public function makeController($validator = null, $service = null, $response = null)
    {
        $validator ??= $this->createStub(Validator::class);
        $service ??= $this->createStub(BetService::class);
        $response ??= $this->createStub(ZirconResponse::class);

        return new ZirconController($validator, $service, $response);
    }

    public function test_sellBet_mockValidator_validateSellBet()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockLib = $this->createMock(Validator::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, [
                'stake' => 'required',
                'roundDetID' => 'required',
                'roundID' => 'required',
                'gameID' => 'required',
                'clientID' => 'required',
                'sessionID' => 'required',
                'ip' => 'required'
            ]);

        $controller = $this->makeController($mockLib);

        $controller->sellBet($request, $game, $player, $bet);
    }

    public function test_sellBet_mockGame_initByGameID()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(1);

        $controller = $this->makeController();

        $controller->sellBet($request, $mockGame, $player, $bet);
    }

    public function test_sellBet_mockPlayer_initBySessionIDGameID()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        
        $bet = $this->createStub(ZirconBet::class);
        $game = $this->createStub(CasinoGame::class);

        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initBySessionIDGameID')
            ->with('sessionID', $game);
        

        $controller = $this->makeController();

        $controller->sellBet($request, $game, $mockPlayer, $bet);
    }

    public function test_sellBet_mockBet_new()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
            
        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('new')
            ->with($player, $game, 'roundDetID');
        
        $controller = $this->makeController();

        $controller->sellBet($request, $game, $player, $mockBet);
    }

    public function test_sellBet_mockBet_setStake()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
            
        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setStake')
            ->with(10.0);
        
        $controller = $this->makeController();

        $controller->sellBet($request, $game, $player, $mockBet);
    }

    public function test_sellBet_mockBet_setIp()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
            
        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setIp')
            ->with('testIp');
        
        $controller = $this->makeController();

        $controller->sellBet($request, $game, $player, $mockBet);
    }

    public function test_sellBet_mockService_startBet()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockService = $this->createMock(BetService::class);
        $mockService->expects($this->once())
            ->method('startBet')
            ->with($player, $game, $bet);
        
        $controller = $this->makeController(null, $mockService);

        $controller->sellBet($request, $game, $player, $bet);
    }

    public function test_sellBet_mockResponse_sellBet()
    {
        $request = new Request([
            'gameID' => 1,
            'sessionID' => 'sessionID',
            'roundDetID' => 'roundDetID',
            'stake' => 10.0,
            'ip' => 'testIp'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockResponse = $this->createMock(ZirconResponse::class);
        $mockResponse->expects($this->once())
            ->method('sellBet')
            ->with($player, $bet);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->sellBet($request, $game, $player, $bet);
    }

    public function test_resultBet_mockValidator_validate()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);
        
        $player = $this->createStub(Player::class);
        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockValidator = $this->createMock(Validator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($request, [
                'roundDetID' => 'required',
                'gameID' => 'required',
                'clientID' => 'required',
                'totalWin' => 'required',
                'turnover' => 'required'
            ]);

        $controller = $this->makeController($mockValidator);
        $controller->resultBet($request, $game, $player, $bet);
    }

    public function test_resultBet_mockGame_initByGameID()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(1);

        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->resultBet($request, $mockGame, $player, $bet);
    }

    public function test_resultBet_mockPlayer_initByClientID()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initByClientID')
            ->with(2);

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->resultBet($request, $game, $mockPlayer, $bet);
    }

    public function test_resultBet_mockBet_initByGamePlayerRoundDetID()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('initByGamePlayerRoundDetID')
            ->with($game, $player, 'roundDetID');

        $controller = $this->makeController();
        $controller->resultBet($request, $game, $player, $mockBet);
    }

    public function test_resultBet_mockBet_setTotalWin()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setTotalWin')
            ->with(10);

        $controller = $this->makeController();
        $controller->resultBet($request, $game, $player, $mockBet);
    }

    public function test_resultBet_mockBet_setTurnover()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setTurnover')
            ->with(5);

        $controller = $this->makeController();
        $controller->resultBet($request, $game, $player, $mockBet);
    }

    public function test_resultBet_mockService_settleBet()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockBet = $this->createMock(BetService::class);
        $mockBet->expects($this->once())
            ->method('settleBet')
            ->with($player, $bet);

        $controller = $this->makeController(null, $mockBet);
        $controller->resultBet($request, $game, $player, $bet);
    }

    public function test_resultBet_mockResponse_resultBet()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID',
            'totalWin' => 10,
            'turnover' => 5
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockResponse = $this->createMock(ZirconResponse::class);
        $mockResponse->expects($this->once())
            ->method('resultBet')
            ->with($player, $bet);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->resultBet($request, $game, $player, $bet);
    }

    public function test_extractBet_mockValidator_validate()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockValidator = $this->createMock(Validator::class);
        $mockValidator->expects($this->once())
            ->method('validate')
            ->with($request, [
                'roundDetID' => 'required',
                'gameID' => 'required',
                'clientID' => 'required'
            ]);

        $controller = $this->makeController($mockValidator);
        $controller->extractBet($request, $game, $player, $bet);
    }

    public function test_extractBet_mockGame_initByGameID()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID'
        ]);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(1);

        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->extractBet($request, $mockGame, $player, $bet);
    }

    public function test_extractBet_mockPlayer_initByClientID()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID'
        ]);

        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initByClientID')
            ->with(2);

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->extractBet($request, $game, $mockPlayer, $bet);
    }

    public function test_extractBet_mockBet_initByGamePlayerRoundDetID()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('initByGamePlayerRoundDetID')
            ->with($game, $player, 'roundDetID');

        $controller = $this->makeController();
        $controller->extractBet($request, $game, $player, $mockBet);
    }

    public function test_extractBet_mockService_checkBet()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockService = $this->createMock(BetService::class);
        $mockService->expects($this->once())
            ->method('checkBet')
            ->with($bet);

        $controller = $this->makeController(null, $mockService);
        $controller->extractBet($request, $game, $player, $bet);
    }

    public function test_extractBet_mockResponse_extractBet()
    {
        $request = new Request([
            'gameID' => 1,
            'clientID' => 2,
            'roundDetID' => 'roundDetID'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockResponse = $this->createMock(ZirconResponse::class);
        $mockResponse->expects($this->once())
            ->method('extractBet')
            ->with($bet);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->extractBet($request, $game, $player, $bet);
    }
}