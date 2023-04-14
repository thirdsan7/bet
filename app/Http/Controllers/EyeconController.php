<?php
namespace App\Http\Controllers;

use App\Entities\Player;
use App\Entities\ZirconBet;
use App\Entities\CasinoGame;
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

    private $validation;
    private $service;
    private $response;


    public function __construct(EyeconValidator $validation, BetService $service, EyeconResponse $response)
    {
        $this->validation = $validation;
        $this->service = $service;
        $this->response = $response;
    }

    public function entry(Request $request, Player $player, CasinoGame $game, ZirconBet $bet)
    {
        $this->validation->validate($request, self::EYECON_REQUEST);

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