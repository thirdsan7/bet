<?php
namespace App\Entities\Interfaces;

use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;

interface IBet
{
    public function getIp(): string;
    public function getStake(): float;
    public function getRefNo(): string;
    public function getRoundDetID(): string;
    public function new(IPlayer $player, IGame $game, string $roundDetID, float $stake, string $ip): void;
    public function create(): void;
    public function settle(): void;
    public function init(IPlayer $player, IGame $game, string $roundDetID, float $totalWin, float $turnover);
    public function getTotalWin(): float;
    public function getTurnover(): float;
}