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

        $controller->sellBet($request, $player, $game, $bet);
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

        $controller->sellBet($request, $player, $mockGame, $bet);
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

        $controller->sellBet($request, $mockPlayer, $game, $bet);
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
            ->with('roundDetID', 10.0, 'testIp');
        
        $controller = $this->makeController();

        $controller->sellBet($request, $player, $game, $mockBet);
    }

    public function test_sellBet_mocService_startBet()
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

        // test try again

        $controller->sellBet($request, $player, $game, $bet);
    }
}