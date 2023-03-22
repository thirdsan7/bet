<?php
namespace App\ThirdPartyApi\Validators;

use Illuminate\Http\Client\Response;
use App\Exceptions\Player\BetLimitException;
use App\Exceptions\ThirdParty\ThirdPartyException;
use App\Exceptions\Player\MaxWinningLimitException;
use App\Exceptions\Player\BalanceNotEnoughException;
use App\Exceptions\Player\PlayerNotLoggedInException;
use App\Exceptions\Transaction\RoundNotFoundException;
use App\Exceptions\Game\SystemUnderMaintenanceException;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Exceptions\Transaction\RoundAlreadySettledException;
use App\Exceptions\Transaction\RoundAlreadyCancelledException;

class ResponseValidator
{

    private function isHttpCode200($status): bool
    {
        if($status !== 200)
            return false;
        
        return true;
    }

    private function isResponseJson($response): bool
    {
        json_decode($response);

        if(json_last_error() !== JSON_ERROR_NONE)
            return false;

        return true;
    }

    private function handleErrorCode($errorCode): void
    {
        switch($errorCode) {
            case 401:
                throw new PlayerNotLoggedInException;
            case 402:
                throw new BalanceNotEnoughException;
            case 403:
                throw new RoundAlreadyExistsException;
            case 404:
                throw new RoundNotFoundException;
            case 405:
                throw new SystemUnderMaintenanceException;
            case 406:
                throw new MaxWinningLimitException;
            case 407:
                throw new BetLimitException;
            case 409:
                throw new RoundAlreadySettledException;
            case 410:
                throw new RoundAlreadyCancelledException;
        }
    }

    public function validate(Response $response): void
    {
        if($this->isHttpCode200($response->status()) === false){
            throw new ThirdPartyException;
        }

        if($this->isResponseJson($response->body()) === false) {
            throw new ThirdPartyException;
        }

        $objResponse = json_decode($response->body());

        $this->handleErrorCode($objResponse->errorCode);
    }
}