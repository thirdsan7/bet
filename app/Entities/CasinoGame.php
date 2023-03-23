<?php
namespace App\Entities;
use App\Entities\Interfaces\IGame;
use App\Exceptions\Game\GameIDNotFoundException;
use App\Repositories\GameRepository;

class CasinoGame implements IGame
{
    const TEST_MODE_ENABLED = 1;
    private $repo;
    private $gameID;
    private $isTestModeEnabled;
    public function __construct(GameRepository $repo)
    {
        $this->repo = $repo;
    }
    
    /**
     * returns gameID
     *
     * @return int
     */
    public function getGameID(): int
    {
        return $this->gameID;
    }
    
    /**
     * initialize class by getting data from DB via Repository with the given gameID
     *
     * @param  int $gameID
     * @return void
     * @throws GameIDNotFoundException
     */
    public function initByGameID(int $gameID): void
    {
        $game = $this->repo->getByGameID($gameID);
        
        if(empty($game))
            throw new GameIDNotFoundException('GameID not found');

        $this->gameID = $game->gameID;
        $this->isTestModeEnabled = $game->isTestModeEnabled;
    }
    
    /**
     * returns a bool depending on the data isTestModeEnabled
     *
     * @return bool
     */
    public function isUnderMaintenance(): bool
    {
        if($this->isTestModeEnabled === self::TEST_MODE_ENABLED)
            return true;

        return false;
    }
}