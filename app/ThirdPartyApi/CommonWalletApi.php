<?php
namespace App\ThirdPartyApi;

use App\Libraries\LaravelLib;
use App\Libraries\LaravelHttp;
use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\ThirdPartyApi\Interfaces\IMotherApi;
use App\ThirdPartyApi\Validators\ResponseValidator;

class CommonWalletApi implements IMotherApi
{
    const RANDOM_STRING_LENGTH = 15;

    private $http;
    private $lib;
    private $validator;
    private $response;

    public function __construct(LaravelHttp $http, LaravelLib $lib, ResponseValidator $validator)
    {
        $this->http = $http;
        $this->lib = $lib;
        $this->validator = $validator;
    }
    
    /**
     * calls CommonWallet API's placeBet
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  IBet $bet
     * @return void
     */
    public function placeBet(IPlayer $player, IGame $game, IBet $bet): void
    {
        $request = [
            'SessionId' => (string) $player->getSessionID(),
            'PlayerIp' => (string) $bet->getIp(),
            'PlayerId' => (string) $player->getClientID(),
            'Bet' => [
                'GameCode' => (string) $game->getGameID(),
                'Stake' => $bet->getStake(),
                'TransactionId' => (string) $bet->getRefNo($game),
            ]
        ];

        $headers = [
            'Authentication' => config('zircon.LC_API_TOKEN'),
            'User-Agent' => config('zircon.LC_API_USER_AGENT'),
            'X-Request-ID' => $this->lib->randomString(self::RANDOM_STRING_LENGTH),
        ];
        
        $this->response = $this->http->post(
            config('zircon.LC_API_URL') . '/' . config('zircon.GAME_PROVIDER_NAME') . "/Bet/PlaceBet",
            $request,
            $headers
        );

        $this->validator->validate($this->response);
    }

    public function settleBet(IPlayer $player, IGame $game, IBet $bet): void
    {
        
    }
    
    /**
     * returns response data from api request.
     *
     * @return object
     * 
     * @codeCoverageIgnore
     */
    public function getData(): object
    {
        $objResponse = json_decode($this->response);

        return $objResponse->data;
    }
}