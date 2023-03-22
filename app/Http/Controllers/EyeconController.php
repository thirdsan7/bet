<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Exceptions\Game\GameIDNotFoundException;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Responses\EyeconResponse;
use Illuminate\Database\QueryException;
use App\Exceptions\General\DBDataNotFound;
use App\Exceptions\Player\BetLimitException;
use App\Exceptions\General\InvalidInputException;
use App\Exceptions\Player\MaxWinningLimitException;
use App\Exceptions\Player\BalanceNotEnoughException;
use App\Exceptions\Player\PlayerNotLoggedInException;
use App\Exceptions\Game\SystemUnderMaintenanceException;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Exceptions\Transaction\RoundAlreadySettledException;
use App\Exceptions\Transaction\RoundAlreadyCancelledException;

class EyeconController extends Controller
{
    const DUPLICATE_ENTRY = 1062;
    
    const EYECON_REQUEST = [
        'uid' => 'required',
        'guid' => 'required',
        'accessid' => 'required',
        'type' => 'required',
        'round' => 'required',
        'gameid' => 'required',
        'ref' => 'required',
        'gtype' => 'required',
        'cur' => 'required',
        'status' => 'required',
        'wager' => 'required',
        'win' => 'required',
        'jpwin' => 'required'
    ];

    private $lib;
    private $service;
    private $response;


    public function __construct(LaravelLib $lib, BetService $service, EyeconResponse $response)
    {
        $this->lib = $lib;
        $this->service = $service;
        $this->response = $response;
    }

    public function entry(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->lib->validate($request, self::EYECON_REQUEST);

        switch($request->type){
            case 'BET':
                return $this->bet($request, $player, $game, $bet);
            default:
                throw new \Exception('Invalid type');
        }
    }

    private function bet(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $game->initByGameID($request->gameid);

        $player->initBySessionIDGameID($request->guid, $game);
        
        $bet->new($request->round, $request->wager, $player->getIp());
        
        $this->service->startBet($player, $game, $bet);

        return $this->response->bet($player);
    }
}