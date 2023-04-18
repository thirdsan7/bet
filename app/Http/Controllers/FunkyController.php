<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Responses\FunkyResponse;
use App\Validators\FunkyValidator;
use App\Validators\Validator;

class FunkyController extends Controller
{
    const PLACE_BET_RULES = [
        'bet.gameCode' => 'required',
        'bet.refNo' => 'required',
        'bet.stake' => 'required',
        'sessionId' => 'required',
        'playerIp' => 'required'
    ];
    private $validator;
    private $service;
    private $response;


    public function __construct(Validator $validator, BetService $service, FunkyResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }

    public function placeBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->validator->validate($request, self::PLACE_BET_RULES);

        $game->initByGameID($request->input('bet.gameCode'));

        $player->initBySessionIDGameID($request->sessionId, $game);
        
        $bet->new($player, $game, $request->input('bet.refNo'), $request->input('bet.stake'), $request->playerIp);
    
        $this->service->startBet($player, $game, $bet);

        return $this->response->placeBet($player);
    }
}