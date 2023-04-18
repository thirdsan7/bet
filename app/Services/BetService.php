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
    
    /**
     * starts the betting process by validating player and game, then creating records and calling third party api
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  IBet $bet
     * @return void
     */
    public function startBet(IPlayer $player, IGame $game, IBet $bet): void
    {
        if($game->isUnderMaintenance() === true && $player->isTestPlayer() === false)  
            throw new SystemUnderMaintenanceException();

        $bet->create();

        $this->api->placeBet($player, $game, $bet);

        $apiData = $this->api->getData();

        $player->setBalance($apiData->balance);
    }

    public function settleBet(IPlayer $player, IGame $game, IBet $bet)
    {
        $bet->settle();

        $this->api->settleBet($player, $game, $bet);

        $apiData = $this->api->getData();

        $player->setBalance($apiData->balance);
    }
}