<?php
namespace App\Responses;

use App\Entities\Interfaces\IBet;
use Illuminate\Http\JsonResponse;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;

class ZirconResponse
{
const RUNNING_EVENT = 'R';    
    /**
     * formatted zircon response for sellBet
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  IBet $bet
     * @return JsonResponse
     */
    public function sellBet(IPlayer $player, IBet $bet): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 0,
                'message' => 'Success'
            ],
            'data' => [
                'roundDetID' => $bet->getRoundDetID(),
                'gameID' => $bet->getGameID(),
                'event' => self::RUNNING_EVENT,
                'balance' => $player->getBalance(),
            ]
        ]);
    }
    
    /**
     * formatted zirocn response for resultBet
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  IBet $bet
     * @return JsonResponse
     */
    public function resultBet(IPlayer $player, IBet $bet): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 0,
                'message' => 'Success'
            ],
            'data' => [
                'roundDetID' => $bet->getRoundDetID(),
                'gameID' => $bet->getGameID(),
                'balance' => $player->getBalance(),
            ]
        ]);
    }
    
}