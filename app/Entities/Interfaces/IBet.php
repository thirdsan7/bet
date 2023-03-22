<?php
namespace App\Entities\Interfaces;

use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;

interface IBet
{
    public function getIp(): string;
    public function getStake(): float;
    public function getRefNo(IGame $game): string;
    public function getRoundDetID(): string;
    public function new(string $roundDetID, float $stake, string $ip): void;
    public function betExists() : bool;
    public function create(IPlayer $player, IGame $game): void;
}