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
    const PLACE_BET_URI = '/Bet/PlaceBet';
    const SETTLE_BET_URI = '/Bet/SettleBet';

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

    private function callApi(array $request, string $uri)
    {
        $headers = [
            'Authentication' => config('zircon.LC_API_TOKEN'),
            'User-Agent' => config('zircon.LC_API_USER_AGENT'),
            'X-Request-ID' => $this->lib->randomString(self::RANDOM_STRING_LENGTH),
        ];
        
        $this->response = $this->http->post(
            config('zircon.LC_API_URL') . '/' . config('zircon.GAME_PROVIDER_NAME') . $uri,
            $request,
            $headers
        );

        $this->validator->validate($this->response);
    }
    
    /**
     * calls CommonWallet API's placeBet
     *
     * @param  IPlayer $player
     * @param  IGame $game
     * @param  IBet $bet
     * @return void
     */
    public function placeBet(IBet $bet): void
    {
        $request = [
            'SessionId' => $bet->getSessionID(),
            'PlayerIp' => (string) $bet->getIp(),
            'PlayerId' => (string) $bet->getClientID(),
            'Bet' => [
                'GameCode' => (string) $bet->getGameID(),
                'Stake' => $bet->getStake(),
                'TransactionId' => $bet->getRefNo(),
            ]
        ];

        $this->callApi($request, self::PLACE_BET_URI);
    }

    public function settleBet(IBet $bet): void
    {
        $request = [
            'TransactionId' => $bet->getRefNo(),
            'BetResultReq' => [
                'WinAmount' => $bet->getTotalWin(),
                'Stake' => $bet->getStake(), 
                'EffectiveStake' => $bet->getTurnover(),
                'PlayerId' => $bet->getClientID(),
                'GameCode' => $bet->getGameID(),
            ]
        ];

        $this->callApi($request, self::SETTLE_BET_URI);
    }
    
    /**
     * returns response data from api request.
     *
     * @return object
     * 
     * @codeCoverageIgnore
     */
    public function getResponse(): object
    {
        $objResponse = json_decode($this->response);

        return $objResponse->data;
    }
}