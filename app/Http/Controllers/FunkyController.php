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
use Illuminate\Http\JsonResponse;

class FunkyController extends Controller
{
    const PLACE_BET_RULES = [
        'bet.gameCode' => 'required',
        'bet.refNo' => 'required',
        'bet.stake' => 'required',
        'sessionId' => 'required',
        'playerIp' => 'required'
    ];
    const SETTLE_BET_RULES = [
        'refNo' => 'required',
        'betResultReq.winAmount' => 'required',
        'betResultReq.stake' => 'required',
        'betResultReq.effectiveStake' => 'required',
        'betResultReq.playerId' => 'required',
        'betResultReq.gameCode' => 'required'
    ];
    private $validator;
    private $service;
    private $response;


    public function __construct(FunkyValidator $validator, BetService $service, FunkyResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }
    
    /**
     * funky's api function to start a bet
     *
     * @param  Request $request
     * @param  Player $player
     * @param  CasinoGame $game
     * @param  ZirconBet $bet
     * @return JsonResponse
     */
    public function placeBet(Request $request, CasinoGame $game, Player $player, ZirconBet $bet)
    {
        $this->validator->validate($request, self::PLACE_BET_RULES);

        $game->initByGameID($request->input('bet.gameCode'));

        $player->initBySessionIDGameID($request->sessionId, $game);
        
        $bet->new($player, $game, $request->input('bet.refNo'));
        $bet->setStake($request->input('bet.stake'));
        $bet->setIp($request->playerIp);
    
        $this->service->startBet($player, $game, $bet);

        return $this->response->placeBet($player);
    }
    
    /**
     * funky's api function to settle a bet
     *
     * @param  Request $request
     * @param  Player $player
     * @param  CasinoGame $game
     * @param  ZirconBet $bet
     * @return JsonResponse
     */
    public function settleBet(Request $request, CasinoGame $game, Player $player, ZirconBet $bet)
    {
        $this->validator->validate($request, self::SETTLE_BET_RULES);

        $game->initByGameID($request->input('betResultReq.gameCode'));

        $player->initByClientID($request->input('betResultReq.playerId'));

        $bet->initByGamePlayerRoundDetID($game, $player, $request->refNo);
        $bet->setTotalWin($request->input('betResultReq.winAmount'));
        $bet->setTurnover($request->input('betResultReq.effectiveStake'));

        $this->service->settleBet($player, $bet);

        return $this->response->settleBet($player, $bet);
    }
}