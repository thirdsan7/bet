<?php
namespace App\Repositories;

use App\Models\Game;
use Illuminate\Database\QueryException;

class GameRepository
{
    private $model;
    public function __construct(Game $model)
    {
        $this->model = $model;
    }
    
    /**
     * get DB data game by gameID
     *
     * @param  int $gameID
     * @return Game|null
     * @throws QueryException
     */
    public function getByGameID(int $gameID): Game|null
    {
       return $this->model
        ->where('gameID', $gameID)
        ->first();
    }
}