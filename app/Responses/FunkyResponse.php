<?php
namespace App\Responses;

use App\Entities\Interfaces\IBet;
use Illuminate\Http\JsonResponse;
use App\Entities\Interfaces\IPlayer;

class FunkyResponse
{    
    /**
     * formatted funky response for placeBet
     *
     * @param  IPlayer $player
     * @return JsonResponse
     */
    public function placeBet(IPlayer $player): JsonResponse
    {
        return response()->json([
            'errorCode' => 0,
            'errorMessage' => 'NoError',
            'data' => [
                'balance' => $player->getBalance()
            ]
        ]);
    }
    
    /**
     * formatted funky response for settleBet
     *
     * @param  IPlayer $player
     * @param  IBet $bet
     * @return JsonResponse
     */
    public function settleBet(IPlayer $player, IBet $bet): JsonResponse
    {
        return response()->json([
            'errorCode' => 0,
            'errorMessage' => 'NoError',
            'data' => [
                'refNo' => $bet->getRoundDetID(),
                'balance' => $player->getBalance(),
                'playerId' => $bet->getClientID(),
                'currency' => '',
                'statementDate' => $bet->getStatementDate()
            ]
        ]);
    }
    
    
}