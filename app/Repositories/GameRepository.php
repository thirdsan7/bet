<?php
namespace App\Repositories;

use App\Models\Game;

class GameRepository
{
    private $model;
    public function __construct(Game $model)
    {
        $this->model = $model;
    }

    public function getByGameID($gameID)
    {
       return $this->model
        ->where('gameID', $gameID)
        ->first();
    }
}