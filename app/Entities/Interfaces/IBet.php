<?php
namespace App\Entities\Interfaces;

use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;

interface IBet
{
    public function getIp(): string;
    public function getStake(): float;
    public function setStake(float $stake): void;
    public function getRoundDetID(): string;
    public function getTotalWin(): float;
    public function getTurnover(): float;
    public function getClientID(): int;
    public function getGameID(): int;
    public function getSessionID(): string;
    public function getRefNo(): string;
    public function setStatementDate(string $statementDate): void;
    public function getStatementDate(): string;
    public function getStatus(): string;
    public function setStatus(string $status): void;
    
    public function new(IPlayer $player, IGame $game, string $roundDetID, float $stake, string $ip): void;
    public function init(IPlayer $player, IGame $game, string $roundDetID, float $totalWin, float $turnover = null): void;
    public function initByGamePlayerRoundDetID(IGame $game, IPlayer $player, string $roundDetID): void;
    public function create(): void;
    public function settle(): void;
    
}