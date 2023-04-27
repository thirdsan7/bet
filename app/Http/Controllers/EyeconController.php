<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Services\BetService;
use Illuminate\Http\Request;
use App\Responses\EyeconResponse;
use App\Validators\EyeconValidator;

class EyeconController extends Controller
{
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

    private $validator;
    private $service;
    private $response;


    public function __construct(EyeconValidator $validator, BetService $service, EyeconResponse $response)
    {
        $this->validator = $validator;
        $this->service = $service;
        $this->response = $response;
    }

    public function entry(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->validator->validate($request, self::EYECON_REQUEST);

        switch($request->type){
            case 'BET':
                return $this->bet($request, $player, $game, $bet);
            case 'WIN':
            case 'LOSE':
                return $this->settle($request, $game, $player, $bet);
            default:
                throw new \Exception('Invalid type');
        }
    }

    private function bet(Request $request, IPlayer $player, IGame $game, IBet $bet)
    {
        $game->initByGameID($request->gameid);

        $player->initBySessionIDGameID($request->guid, $game);
        
        $bet->new($player, $game, $request->round, $request->wager, $player->getIp());
        
        $this->service->startBet($player, $game, $bet);

        return $this->response->balance($player);
    }

    private function settle(Request $request, IGame $game, IPlayer $player, IBet $bet)
    {
        $game->initByGameID($request->gameid);

        $player->initByClientID($request->uid);

        $bet->initByGamePlayerRoundDetID($game, $player, $request->round);
        $bet->setTotalWin($request->win);

        $this->service->settleBet($player, $bet);

        return $this->response->balance($player);
    }
}