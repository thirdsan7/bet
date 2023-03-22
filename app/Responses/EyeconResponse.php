<?php
namespace App\Responses;

use Illuminate\Http\Response;
use App\Entities\Interfaces\IPlayer;

class EyeconResponse
{
    public function bet(IPlayer $player): Response
    {
        return response("status=ok&bal={$player->getBalance()}");
    }

    public function invalidInput(string $message): Response
    {
        return response('', 500);
    }

    public function invalidGameID(): Response
    {
        return response('status=invalid&error=3');
    }

    public function playerNotLoggedIn(): Response
    {
        return response('status=invalid&error=12');
    }

    public function systemUnderMaintenance(): Response
    {
        return response('', 503);
    }

    public function betAlreadyExists(): Response
    {
        return response('status=invalid&error=15');
    }

    public function somethingWentWrong(string $message): Response
    {
        return response($message, 500);
    }

    public function balanceNotEnough(): Response
    {
        return response('status=invalid&error=13');
    }

    public function maxWinningExceed(): Response
    {
        return response('status=invalid&error=21');
    }

    public function betLimitExceed(): Response
    {
        return response('status=invalid&error=21');
    }

    public function betAlreadySettled(): Response
    {
        return response('status=invalid&error=15');
    }

    public function betAlreadyCancelled(): Response
    {
        return response('status=invalid&error=16');
    }
}