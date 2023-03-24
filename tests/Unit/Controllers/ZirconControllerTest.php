<?php

use App\Entities\CasinoGame;
use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Http\Controllers\ZirconController;
use App\Libraries\LaravelLib;
use App\Responses\ZirconResponse;
use App\Services\BetService;
use Illuminate\Http\Request;
use Tests\TestCase;

class ZirconControllerTest extends TestCase
{
    public function makeController($lib = null, $service = null, $response = null)
    {
        $lib ??= $this->createStub(LaravelLib::class);
        $service ??= $this->createStub(BetService::class);
        $response ??= $this->createStub(ZirconResponse::class);

        return new ZirconController($lib, $service, $response);
    }

    public function test_sellBet_mockLib_validate()
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

        $mockLib = $this->createMock(LaravelLib::class);
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
}