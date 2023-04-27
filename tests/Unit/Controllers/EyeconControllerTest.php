<?php

use Tests\TestCase;
use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Responses\EyeconResponse;
use App\Http\Controllers\EyeconController;
use App\Validators\EyeconValidator;

class EyeconControllerTest extends TestCase
{
    public function makeController($validation = null, $service = null, $response = null)
    {
        $validation ??= $this->createStub(EyeconValidator::class);
        $service ??= $this->createStub(BetService::class);
        $response ??= $this->createStub(EyeconResponse::class);

        return new EyeconController($validation, $service, $response);
    }

    public function test_entry_mockValidation_validate()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockValidation = $this->createMock(EyeconValidator::class);
        $mockValidation->expects($this->once())
            ->method('validate')
            ->with($request);

        $controller = $this->makeController($mockValidation);
        $controller->entry($request, $game, $player, $bet);
    }

    public function test_entry_typeUnknown_exception()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'TEST',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $this->expectException(Exception::class);
        
        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $game = $this->createMock(CasinoGame::class);    
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->entry($request, $game, $player, $bet);
    }

    public function test_entry_typeBetMockGame_initByGameID()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(1);

        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');
            
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->entry($request, $mockGame, $player, $bet);
    }

    public function test_entry_typeBetMockPlayer_initBySessionIDGameID()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);

        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initBySessionIDGameID')
            ->with('guid', $game);

        $mockPlayer->method('getIp')
            ->willReturn('ipAddress');
            
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->entry($request, $game, $mockPlayer, $bet);
    }

    public function test_entry_typeBetMockBet_new()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $mockBet = $this->createStub(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('new')
            ->with($player, $game, 'round');

        $controller = $this->makeController();
        $controller->entry($request, $game, $player, $mockBet);
    }

    public function test_entry_typeBetMockBet_setStake()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $mockBet = $this->createStub(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setStake')
            ->with(10);

        $controller = $this->makeController();
        $controller->entry($request, $game, $player, $mockBet);
    }

    public function test_entry_typeBetMockBet_setIp()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $mockBet = $this->createStub(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setIp')
            ->with('ipAddress');

        $controller = $this->makeController();
        $controller->entry($request, $game, $player, $mockBet);
    }

    public function test_entry_typeBetMockService_startBet()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockService = $this->createMock(BetService::class);
        $mockService->expects($this->once())
            ->method('startBet')
            ->with($player, $game, $bet);

        $controller = $this->makeController(null, $mockService);
        $controller->entry($request, $game, $player, $bet);
    }

    public function test_entry_typeBetMockResponse_balance()
    {
        $request = new Request([
            'uid' => 'uid',
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 'round',
            'gameid' => 1,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $player = $this->createStub(Player::class);
        $player->method('getIp')
            ->willReturn('ipAddress');

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockResponse = $this->createMock(EyeconResponse::class);
        $mockResponse->expects($this->once())
            ->method('balance')
            ->with($player);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->entry($request, $game, $player, $bet);
    }

    public function test_entry_typeWinMockGame_initByGameID()
    {
        $request = new Request([
            'uid' => 1,
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'WIN',
            'round' => 'round',
            'gameid' => 2,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 100,
            'jpwin' => 'jpwin'
        ]);

        $mockGame = $this->createMock(CasinoGame::class);
        $mockGame->expects($this->once())
            ->method('initByGameID')
            ->with(2);

        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->entry($request, $mockGame, $player, $bet);
    }

    public function test_entry_typeWinMockPlayer_initByClientID()
    {
        $request = new Request([
            'uid' => 1,
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'WIN',
            'round' => 'round',
            'gameid' => 2,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 100,
            'jpwin' => 'jpwin'
        ]);


        $mockPlayer = $this->createMock(Player::class);
        $mockPlayer->expects($this->once())
            ->method('initByClientID')
            ->with(1);

        $game = $this->createStub(CasinoGame::class);
        $bet = $this->createStub(ZirconBet::class);

        $controller = $this->makeController();
        $controller->entry($request, $game, $mockPlayer, $bet);
    }

    public function test_entry_typeWinMockBet_initByGamePlayerRoundDetID()
    {
        $request = new Request([
            'uid' => 1,
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'WIN',
            'round' => 'round',
            'gameid' => 2,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 100,
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('initByGamePlayerRoundDetID')
            ->with($game, $player, 'round');

        $controller = $this->makeController();
        $controller->entry($request, $game, $player, $mockBet);
    }

    public function test_entry_typeWinMockBet_setTotalWin()
    {
        $request = new Request([
            'uid' => 1,
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'WIN',
            'round' => 'round',
            'gameid' => 2,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 100,
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);

        $mockBet = $this->createMock(ZirconBet::class);
        $mockBet->expects($this->once())
            ->method('setTotalWin')
            ->with(100);

        $controller = $this->makeController();
        $controller->entry($request, $game, $player, $mockBet);
    }

    public function test_entry_typeWinMockService_settleBet()
    {
        $request = new Request([
            'uid' => 1,
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'WIN',
            'round' => 'round',
            'gameid' => 2,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 100,
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockService = $this->createMock(BetService::class);
        $mockService->expects($this->once())
            ->method('settleBet')
            ->with($player, $bet);

        $controller = $this->makeController(null, $mockService);
        $controller->entry($request, $game, $player, $bet);
    }

    public function test_entry_typeWinMockResponse_balance()
    {
        $request = new Request([
            'uid' => 1,
            'guid' => 'guid',
            'accessid' => 'accessid',
            'type' => 'WIN',
            'round' => 'round',
            'gameid' => 2,
            'ref' => 'ref',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'status',
            'wager' => 10,
            'win' => 100,
            'jpwin' => 'jpwin'
        ]);

        $game = $this->createStub(CasinoGame::class);
        $player = $this->createStub(Player::class);
        $bet = $this->createStub(ZirconBet::class);

        $mockResponse = $this->createMock(EyeconResponse::class);
        $mockResponse->expects($this->once())
            ->method('balance')
            ->with($player);

        $controller = $this->makeController(null, null, $mockResponse);
        $controller->entry($request, $game, $player, $bet);
    }
}