<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Exceptions\Game\GameIDNotFoundException;
use App\Exceptions\Game\SystemUnderMaintenanceException;
use App\Exceptions\General\InvalidInputException;
use App\Exceptions\Player\BalanceNotEnoughException;
use App\Exceptions\Player\BetLimitException;
use App\Exceptions\Player\MaxWinningLimitException;
use App\Exceptions\Player\PlayerNotLoggedInException;
use App\Exceptions\Transaction\RoundAlreadyCancelledException;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Exceptions\Transaction\RoundAlreadySettledException;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Responses\ZirconResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

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

    private $lib;
    private $service;
    private $response;

    public function __construct(LaravelLib $lib, BetService $service, ZirconResponse $response)
    {
        $this->lib = $lib;
        $this->service = $service;
        $this->response = $response;
    }
    
    public function sellBet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet): JsonResponse
    {
        $this->lib->validate($request, self::SELL_BET_RULES);

        $game->initByGameID($request->gameID);

        $player->initBySessionIDGameID($request->sessionID, $game);
        
        $bet->new($request->roundDetID, $request->stake, $request->ip);
        
        $this->service->startBet($player, $game, $bet);

        return $this->response->sellBet($player, $game, $bet);
    }
}