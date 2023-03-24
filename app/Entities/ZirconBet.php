<?php
namespace App\Entities;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Repositories\TransactionRepository;
use Illuminate\Database\QueryException;

class ZirconBet implements IBet
{
    private $repo;

    private $roundDetID;
    private $stake;
    private $ip;

    public function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }
        
    /**
     * returns roundDetID
     *
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function getRoundDetID(): string
    {
        return $this->roundDetID;
    }
    
    /**
     * returns ip
     *
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function getIp(): string
    {
        return $this->ip;
    }
    
    /**
     * returns stake
     *
     * @return float
     * 
     * @codeCoverageIgnore
     */
    public function getStake(): float
    {
        return $this->stake;
    }
    
    /**
     * returns formatted refNo based on roundDetID, gameID from given Game and environment id
     *
     * @param  IGame $game
     * @return string
     */
    public function getRefNo(IGame $game): string
    {
        return "{$this->roundDetID}-{$game->getGameID()}-".config('zircon.ENV_ID');
    }
    
    /**
     * initialize class's bet data
     *
     * @param  string $roundDetID
     * @param  float $stake
     * @param  string $ip
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function new(string $roundDetID, float $stake, string $ip): void
    {
        $this->roundDetID = $roundDetID;
        $this->stake = $stake;
        $this->ip = $ip;
    }
    
    /**
     * creates data to the DB via repository
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @return void
     * @throws QueryException
     */
    public function create(IPlayer $player, IGame $game): void
    {
        $this->repo->create(
            $this->roundDetID,
            $player->getClientID(),
            $player->getSessionID(),
            $game->getGameID(),
            $this->stake,
            $this->getRefNo($game)
        );
    }
}