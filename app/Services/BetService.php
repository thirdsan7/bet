<?php
namespace App\Services;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\ThirdPartyApi\CommonWalletApi;
use App\Exceptions\Game\SystemUnderMaintenanceException;

class BetService
{
    private $api;

    public function __construct(CommonWalletApi $api)
    {
        $this->api = $api;
    }

    public function startBet(IPlayer $player, IGame $game, IBet $bet)
    {
        if($game->isUnderMaintenance() === true && $player->isTestPlayer() === false)  
            throw new SystemUnderMaintenanceException();

        $bet->create($player, $game);

        $this->api->placeBet($player, $game, $bet);

        $apiData = $this->api->getData();

        $player->setBalance($apiData->balance);
    }
}