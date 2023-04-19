<?php
namespace App\ThirdPartyApi\Interfaces;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;

interface IMotherApi
{
    public function placeBet(IBet $bet): void;
}