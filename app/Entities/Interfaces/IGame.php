<?php
namespace App\Entities\Interfaces;

interface IGame
{
    public function initByGameID(int $gameID): void;
    public function isUnderMaintenance(): bool;
    public function getGameID(): int;
}