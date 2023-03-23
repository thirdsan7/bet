<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Responses\FunkyResponse;

class FunkyController extends Controller
{
    const DUPLICATE_ENTRY = 1062;

    const PLACE_BET_RULES = [
        'bet.gameCode' => 'required',
        'bet.refNo' => 'required',
        'bet.stake' => 'required',
        'sessionId' => 'required',
        'playerIp' => 'required'
    ];

    private $lib;
    private $service;
    private $response;


    public function __construct(LaravelLib $lib, BetService $service, FunkyResponse $response)
    {
        $this->lib = $lib;
        $this->service = $service;
        $this->response = $response;
    }

    public function placeBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->lib->validate($request, self::PLACE_BET_RULES);

        $game->initByGameID($request->input('bet.gameCode'));

        $player->initBySessionIDGameID($request->sessionId, $game);
        
        $bet->new($request->input('bet.refNo'), $request->input('bet.stake'), $request->playerIp);
    
        $this->service->startBet($player, $game, $bet);

        return $this->response->placeBet($player);
    }
}