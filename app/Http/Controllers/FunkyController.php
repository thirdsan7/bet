<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Responses\FunkyResponse;
use App\Validators\FunkyValidator;

class FunkyController extends Controller
{
    private $validator;
    private $service;
    private $response;


    public function __construct(FunkyValidator $validator, BetService $service, FunkyResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }

    public function placeBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->validator->validateSellBet($request);

        $game->initByGameID($request->input('bet.gameCode'));

        $player->initBySessionIDGameID($request->sessionId, $game);
        
        $bet->new($request->input('bet.refNo'), $request->input('bet.stake'), $request->playerIp);
    
        $this->service->startBet($player, $game, $bet);

        return $this->response->placeBet($player);
    }
}