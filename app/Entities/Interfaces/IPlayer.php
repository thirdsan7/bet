<?php
namespace App\Entities\Interfaces;

use App\Entities\Interfaces\IGame;

interface IPlayer
{
    public function initBySessionIDGameID(string $sessionID, IGame $game): void;
    public function isTestPlayer(): bool;
    public function getClientID(): int;
    public function getSessionID(): string;
    public function setBalance(float $balance): void;
    public function getBalance(): float;
    public function getIp(): string|null;
    public function initByClientID(int $clientID): void;
}