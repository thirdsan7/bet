<?php
namespace App\Entities;

use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Repositories\PlayerRepository;
use App\Exceptions\Player\PlayerNotLoggedInException;

class Player implements IPlayer
{
    const TEST_PLAYER = 1;
    private $repo;
    private $clientID;
    private $sessionID;
    private $ip;
    private $balance;
    private $isTestPlayer;

    public function __construct(PlayerRepository $repo){
        $this->repo = $repo;
    }
        
    /**
     * returns clientID
     *
     * @return int
     * 
     * @codeCoverageIgnore
     */
    public function getClientID(): int
    {
        return $this->clientID;
    }
        
    /**
     * returns sessionID
     *
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function getSessionID(): string
    {
        return $this->sessionID;
    }
    
    /**
     * sets balance base on given data
     *
     * @param  float $balance
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }
    
    /**
     * returns balance
     *
     * @return float
     * 
     * @codeCoverageIgnore
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
    
    /**
     * initialize class by getting data from DB via Repository with the given sessionID and Game
     *
     * @param  string $sessionID
     * @param  IGame $game
     * @return void
     * @throws PlayerNotLoggedInException
     */
    public function initBySessionIDGameID(string $sessionID, IGame $game): void
    {
        $player = $this->repo->getBySessionIDGameID($sessionID, $game->getGameID());

        if(empty($player))
            throw new PlayerNotLoggedInException('Game Session not found');

        $this->clientID = $player->sboClientID;
        $this->sessionID = $player->sessionID;
        $this->ip = $player->loginIP;
        $this->isTestPlayer = $player->isTestPlayer;
    }
    
    /**
     * initialize class by gettind data from DB with given clientID
     *
     * @param  mixed $clientID
     * @return void
     */
    public function initByClientID(int $clientID): void
    {
        $player = $this->repo->getByClientID($clientID);
    }
    
    /**
     * returns a bool depending on the data isTestPlayer
     *
     * @return bool
     */
    public function isTestPlayer(): bool
    {
        if($this->isTestPlayer === self::TEST_PLAYER)
            return true;

        return false;
    }
    
    /**
     * returns ip
     *
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function getIp(): string|null
    {
        return $this->ip;
    }
}