<?php
namespace App\Responses;

use Illuminate\Http\Response;
use App\Entities\Interfaces\IPlayer;

class EyeconResponse
{    
    /**
     * formatted eyecon response for bet
     *
     * @param  IPlayer $player
     * @return Response
     */
    public function bet(IPlayer $player): Response
    {
        return response("status=ok&bal={$player->getBalance()}");
    }
    
    
}