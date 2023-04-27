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

    private $roundDetID = null;
    private $stake = 0;
    private $ip = null;
    private $totalWin = 0;
    private $turnover = 0;
    private $transactionID = null;
    private $gameID = 0;
    private $sboClientID = 0;
    private $sessionID = null;
    private $statementDate = null;
    private $status = null;

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
     * set stake
     *
     * @param  float $stake
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function setStake(float $stake): void
    {
        $this->stake = $stake;
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
     * sets statementDate
     *
     * @param  string $statementDate
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function setStatementDate(string $statementDate): void
    {
        $this->statementDate = $statementDate;
    }

    /**
     * returns statementDate
     *
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function getStatementDate(): string
    {
        return $this->statementDate;
    }
    
    /**
     * returns status
     *
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * sets status
     *
     * @param  string $status
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * initialize class's bet data
     *
     * @param  string $roundDetID
     * @param  float $stake
     * @param  string $ip
     * @return void
     * 
     */
    public function new(IPlayer $player, IGame $game, string $roundDetID, float $stake, string $ip): void
    {
        $transaction = $this->repo->getBySboClientIDGameIDRoundDetID(
            $player->getClientID(),
            $game->getGameID(),
            $roundDetID
        );

        if (!empty($transaction))
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

    /**
     * initialize bet class via getting data from DB
     * set stake as turnover if no turnover provided
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  string $roundDetID
     * @param  float $totalWin
     * @param  float $turnover
     * @return void
     */
    public function init(
        IPlayer $player, IGame $game, string $roundDetID, float $totalWin, float $turnover = null
    ): void {
        $transaction = $this->repo->getBySboClientIDGameIDRoundDetID(
            $player->getClientID(),
            $game->getGameID(),
            $roundDetID
        );

        if (empty($transaction))
            throw new RoundNotFoundException;

        $this->roundDetID = $roundDetID;
        $this->totalWin = $totalWin;
        $this->turnover = $turnover ?? $transaction->stake;
        $this->stake = $transaction->stake;
        $this->transactionID = $transaction->transactionCWID;
        $this->sboClientID = $player->getClientID();
        $this->gameID = $game->getGameID();
    }

    public function initByGamePlayerRoundDetID(IGame $game, IPlayer $player, string $roundDetID): void
    {
        $transaction = $this->repo->getBySboClientIDGameIDRoundDetID(
            $player->getClientID(),
            $game->getGameID(),
            $roundDetID
        );

        if (empty($transaction))
            throw new RoundNotFoundException;

        $this->roundDetID = $roundDetID;
        $this->stake = $transaction->stake;
        $this->transactionID = $transaction->transactionCWID;
        $this->sboClientID = $player->getClientID();
        $this->gameID = $game->getGameID();
    }

    /**
     * returns corresponding event based on totalWin
     *
     * @return string
     */
    private function getEvent(): string
    {
        if ($this->totalWin > 0)
            return self::WIN;

        return self::LOSE;
    }

    /**
     * updates DB's totalWin, turnover and event.
     *
     * @return void
     */
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