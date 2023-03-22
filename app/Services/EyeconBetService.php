<?php
namespace App\Services;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;

class EyeconBetService extends BetService
{
    public function startBet(IPlayer $player, IGame $game, IBet $bet): void
    {
        //additional validation for eyecon

        parent::startBet($player, $game, $bet);
    }
}