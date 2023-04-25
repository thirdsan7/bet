<?php
namespace App\Responses;

use Illuminate\Http\Response;
use App\Entities\Interfaces\IPlayer;

class EyeconResponse
{   
    /**
     * formatted eyecon balance response 
     *
     * @param  IPlayer $player
     * @return Response
     */
    public function balance(IPlayer $player): Response
    {
        return response("status=ok&bal={$player->getBalance()}");
    }
}