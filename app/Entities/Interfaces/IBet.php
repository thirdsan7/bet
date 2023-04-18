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
    public function create(IPlayer $player, IGame $game): void;
    public function settle(): void;
    public function init(IPlayer $player, IGame $game, string $roundDetID, float $totalWin, float $turnover);
}