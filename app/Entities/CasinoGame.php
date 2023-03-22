<?php
namespace App\Entities;
use App\Entities\Interfaces\IGame;
use App\Exceptions\Game\GameIDNotFoundException;
use App\Exceptions\General\DBDataNotFound;
use App\Exceptions\General\InvalidInputException;
use App\Repositories\GameRepository;

class CasinoGame implements IGame
{
    private $repo;
    private $gameID;
    private $isTestModeEnabled;
    public function __construct(GameRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getGameID(): int
    {
        return $this->gameID;
    }

    public function initByGameID(int $gameID): void
    {
        $game = $this->repo->getByGameID($gameID);
        
        if(empty($game))
            throw new GameIDNotFoundException('GameID not found');

        $this->gameID = $game->gameID;
        $this->isTestModeEnabled = $game->isTestModeEnabled;
    }

    public function isUnderMaintenance(): bool
    {
        if($this->isTestModeEnabled == 1)
            return true;

        return false;
    }
}