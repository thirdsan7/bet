<?php
namespace App\Entities;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Exceptions\Transaction\RoundNotFoundException;
use App\Repositories\TransactionRepository;
use Illuminate\Database\QueryException;

class ZirconBet implements IBet
{
    const WIN = 'W';
    const LOSE = 'L';

    private $repo;

    private $roundDetID;
    private $stake;
    private $ip;
    private $totalWin;
    private $turnover;
    private $transactionID;
    private $gameID;
    private $sboClientID;
    private $sessionID;

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
     * getTotalWin
     *
     * @return float
     * 
     * @codeCoverageIgnore
     */
    public function getTotalWin(): float
    {
        return $this->totalWin;
    }
    
    /**
     * getTurnover
     *
     * @return float
     * 
     * @codeCoverageIgnore
     */
    public function getTurnover(): float
    {
        return $this->turnover;
    }
    
    /**
     * getClientID
     *
     * @return int
     * 
     * @codeCoverageIgnore
     */
    public function getClientID(): int
    {
        return $this->sboClientID;
    }
    
    /**
     * getGameID
     *
     * @return int
     * 
     * @codeCoverageIgnore
     */
    public function getGameID(): int
    {
        return $this->gameID;
    }

    /**
     * getGameID
     *
     * @return int
     * 
     * @codeCoverageIgnore
     */
    public function getSessionID(): string
    {
        return $this->sessionID;
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
    public function new(IPlayer $player, IGame $game, string $roundDetID, float $stake, string $ip): void
    {
        $transaction = $this->repo->getBySboClientIDGameIDRoundDetID(
            $player->getClientID(), 
            $game->getGameID(),
            $roundDetID
        );

        if(!empty($transaction)) 
            throw new RoundAlreadyExistsException;

        $this->roundDetID = $roundDetID;
        $this->stake = $stake;
        $this->ip = $ip;
        $this->gameID = $game->getGameID();
        $this->sboClientID = $player->getClientID();
        $this->sessionID = $player->getSessionID();
    }

    /**
     * returns formatted refNo based on roundDetID, gameID from given Game and environment id
     *
     * @param  IGame $game
     * @return string
     */
    public function getRefNo(): string
    {
        return "{$this->roundDetID}-{$this->gameID}-" . config('zircon.ENV_ID');
    }

    /**
     * creates data to the DB via repository
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @return void
     * @throws QueryException
     */
    public function create(): void
    {
        $this->repo->create(
            $this->roundDetID,
            $this->sboClientID,
            $this->sessionID,
            $this->gameID,
            $this->stake,
            $this->getRefNo()
        );
    }

    public function init(IPlayer $player, IGame $game, string $roundDetID, float $totalWin, float $turnover)
    {
        $transaction = $this->repo->getBySboClientIDGameIDRoundDetID(
            $player->getClientID(), 
            $game->getGameID(),
            $roundDetID
        );
        
        if(empty($transaction)) 
            throw new RoundNotFoundException;

        $this->roundDetID = $roundDetID;
        $this->totalWin = $totalWin;
        $this->turnover = $turnover;
        $this->stake = $transaction->stake;
        $this->transactionID = $transaction->transactionCWID;
        $this->sboClientID = $player->getClientID();
        $this->gameID = $game->getGameID();
    }

    private function getEvent()
    {
        if($this->totalWin > 0)
            return self::WIN;

        return self::LOSE;
    }

    public function settle(): void
    {
        $this->repo->updateByTransactionID(
            [
                'totalWin' => $this->totalWin,
                'turnover' => $this->turnover,
                'event' => $this->getEvent()
            ],
            $this->transactionID
        );
    }
}