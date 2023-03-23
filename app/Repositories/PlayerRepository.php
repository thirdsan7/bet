<?php
namespace App\Repositories;

use App\Models\LoginInfo;
use Illuminate\Database\QueryException;

class PlayerRepository
{
    private $model;

    public function __construct(LoginInfo $model)
    {
        $this->model = $model;
    }
        
    /**
     * returns getlogininfo joined with gameloginsession and testplayer table data via sessionid and gameid
     *
     * @param  int $sessionID
     * @param  int $gameID
     * @return LoginInfo|null
     * @throws QueryException
     */
    public function getBySessionIDGameID(int $sessionID, int $gameID): LoginInfo|null
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