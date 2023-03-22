<?php
namespace App\Entities;

use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Repositories\PlayerRepository;
use App\Exceptions\Player\PlayerNotLoggedInException;

class Player implements IPlayer
{
    private $repo;
    private $clientID;
    private $sessionID;
    private $ip;
    private $balance;
    private $isTestPlayer;

    public function __construct(PlayerRepository $repo){
        $this->repo = $repo;
    }

    public function getClientID(): int
    {
        return $this->clientID;
    }

    public function getSessionID(): string
    {
        return $this->sessionID;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function initBySessionIDGameID(int $sessionID, IGame $game): void
    {
        $player = $this->repo->getBySessionIDGameID($sessionID, $game->getGameID());

        if(empty($player))
            throw new PlayerNotLoggedInException('Game Session not found');

        $this->clientID = $player->sboClientID;
        $this->sessionID = $player->sessionID;
        $this->ip = $player->loginIP;
        $this->isTestPlayer = $player->isTestPlayer;
    }

    public function isTestPlayer(): bool
    {
        if($this->isTestPlayer === 1)
            return true;

        return false;
    }

    public function getIp(): string|null
    {
        return $this->ip;
    }
}