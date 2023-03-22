<?php
namespace App\Entities;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Repositories\TransactionRepository;

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

    public function getRoundDetID(): string
    {
        return $this->roundDetID;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getStake(): float
    {
        return $this->stake;
    }

    public function getRefNo(IGame $game): string
    {
        return "{$this->roundDetID}-{$game->getGameID()}-".config('zircon.ENV_ID');
    }

    public function new(string $roundDetID, float $stake, string $ip): void
    {
        $this->roundDetID = $roundDetID;
        $this->stake = $stake;
        $this->ip = $ip;
    }

    public function betExists(): bool
    {
        return false;
    }

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

    public function beginTransaction()
    {
        $this->repo->beginTransaction();
    }

    public function commit()
    {
        $this->repo->commit();
    }

    public function rollback()
    {
        $this->repo->rollBack();
    }
}