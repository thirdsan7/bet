<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Entities\Interfaces\IBet;
use App\Responses\ZirconResponse;
use Illuminate\Http\JsonResponse;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
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
     * Zircon API sellBet which starts the bet of a player.
     *
     * @param  Request $request
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  IBet $bet
     * @return JsonResponse
     */
    public function sellBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet): JsonResponse
    {
        $this->validator->validate($request, self::SELL_BET_RULES);

        $game->initByGameID($request->gameID);

        $player->initBySessionIDGameID($request->sessionID, $game);
        
        $bet->new($request->roundDetID, $request->stake, $request->ip);
        
        $this->service->startBet($player, $game, $bet);

        return $this->response->sellBet($player, $game, $bet);
    }

    public function resultBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->validator->validate($request, self::RESULT_BET_RULES);

        $game->initByGameID($request->gameID);

        $player->initByClientID($request->clientID);

        $bet->init($player, $game, $request->roundDetID, $request->totalWin, $request->turnover);

        $this->service->settleBet($player, $game, $bet);

        $this->response->resultBet($player, $game, $bet);
    }
}