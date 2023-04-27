<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Responses\ZirconResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Validators\Validator;

class ZirconController extends Controller
{
    const SELL_BET_RULES = [
        'stake' => 'required',
        'roundDetID' => 'required',
        'roundID' => 'required',
        'gameID' => 'required',
        'clientID' => 'required',
        'sessionID' => 'required',
        'ip' => 'required'
    ];

    const RESULT_BET_RULES = [
        'roundDetID' => 'required',
        'gameID' => 'required',
        'clientID' => 'required',
        'totalWin' => 'required',
        'turnover' => 'required'
    ];

    const EXTRACT_BET_RULES = [
        'roundDetID' => 'required',
        'gameID' => 'required',
        'clientID' => 'required'
    ];

    private $validator;
    private $service;
    private $response;

    public function __construct(Validator $validator, BetService $service, ZirconResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }
        
    /**
     * Zircon API sellBet to start the bet of a player.
     *
     * @param  Request $request
     * @param  Player $player
     * @param  CasinoGame $game
     * @param  ZirconBet $bet
     * @return JsonResponse
     */
    public function sellBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet): JsonResponse
    {
        $this->validator->validate($request, self::SELL_BET_RULES);

        $game->initByGameID($request->gameID);

        $player->initBySessionIDGameID($request->sessionID, $game);
        
        $bet->new($player, $game, $request->roundDetID, $request->stake, $request->ip);
        
        $this->service->startBet($player, $game, $bet);

        return $this->response->sellBet($player, $bet);
    }
    
    /**
     * Zircon API resultBet to settle the bet of a player
     *
     * @param  Request $request
     * @param  Player $player
     * @param  CasinoGame $game
     * @param  ZirconBet $bet
     * @return JsonResponse
     */
    public function resultBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->validator->validate($request, self::RESULT_BET_RULES);

        $game->initByGameID($request->gameID);

        $player->initByClientID($request->clientID);

        $bet->init($player, $game, $request->roundDetID, $request->totalWin, $request->turnover); //refactor or rename

        $this->service->settleBet($player, $bet);

        return $this->response->resultBet($player, $bet);
    }
    
    /**
     * zircon API extractBet is to get the details of the bet
     * 
     * @param Request $request
     *
     * @return void
     */
    public function extractBet(Request $request, CasinoGame $game, Player $player, ZirconBet $bet)
    {
        $this->validator->validate($request, self::EXTRACT_BET_RULES);

        $game->initByGameID($request->gameID);

        $player->initByClientID($request->clientID);

        $bet->initByGamePlayerRoundDetID($game, $player, $request->roundDetID);

        $this->service->checkBet($bet);

        return $this->response->extractBet($bet);
    }
}