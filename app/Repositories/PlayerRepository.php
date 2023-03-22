<?php
namespace App\Repositories;

use App\Models\GameLoginSession;
use App\Models\LoginInfo;

class PlayerRepository
{
    private $model;

    public function __construct(LoginInfo $model)
    {
        $this->model = $model;
    }

    public function getBySessionIDGameID($sessionID, $gameID)
    {
        return $this->model->select('getlogininfo.*', 'gameloginsession.*', 'testplayer.isTestPlayer')
            ->where('getlogininfo.sessionID', $sessionID)
            ->where('gameloginsession.gameID', $gameID)
            ->join('gameloginsession', 'getlogininfo.getLoginInfoID', '=', 'gameloginsession.getLoginInfoID')
            ->leftJoin('testplayer', 'getlogininfo.sboClientID', '=', 'testplayer.sboClientID')
            ->orderBy('getlogininfo.getLoginInfoID', 'desc')
            ->first();
    }
}