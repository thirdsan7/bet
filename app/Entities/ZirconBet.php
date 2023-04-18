<?php
namespace App\Entities;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
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
        return "{$this->roundDetID}-{$game->getGameID()}-" . config('zircon.ENV_ID');
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
        $this->transactionID = $transaction->transactionID;
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