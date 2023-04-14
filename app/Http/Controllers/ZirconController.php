<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Entities\Interfaces\IBet;
use App\Responses\ZirconResponse;
use Illuminate\Http\JsonResponse;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Http\Controllers\Controller;
use App\Validators\ZirconValidator;

class ZirconController extends Controller
{
    private $validator;
    private $service;
    private $response;

    public function __construct(ZirconValidator $validator, BetService $service, ZirconResponse $response)
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
        $this->validator->validateSellBet($request);

        $game->initByGameID($request->gameID);

        $player->initBySessionIDGameID($request->sessionID, $game);
        
        $bet->new($request->roundDetID, $request->stake, $request->ip);
        
        $this->service->startBet($player, $game, $bet);

        return $this->response->sellBet($player, $game, $bet);
    }
}