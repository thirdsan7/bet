<?php
namespace App\Responses;

use Illuminate\Http\JsonResponse;
use App\Entities\Interfaces\IPlayer;
use Illuminate\Http\Response;

class FunkyResponse
{
    public function placeBet(IPlayer $player): JsonResponse
    {
        return response()->json([
            'errorCode' => 0,
            'errorMessage' => 'NoError',
            'data' => [
                'balance' => $player->getBalance()
            ]
        ]);
    }

    public function invalidInput(string $message): JsonResponse
    {
        return response()->json([
            'errorCode' => 400,
            'errorMessage' => 'Invalid input',
        ]);
    }

    public function invalidGameID(): JsonResponse
    {
        return response()->json([
            'errorCode' => 400,
            'errorMessage' => 'Invalid input',
        ]);
    }

    public function playerNotLoggedIn(): JsonResponse
    {
        return response()->json([
            'errorCode' => 401,
            'errorMessage' => 'Player is not login',
        ]);
    }

    public function systemUnderMaintenance(): JsonResponse
    {
        return response()->json([
            'errorCode' => 405,
            'errorMessage' => 'API suspended',
        ]);
    }

    public function betAlreadyExists(): JsonResponse
    {
        return response()->json([
            'errorCode' => 403,
            'errorMessage' => 'Bet already exists',
        ]);
    }

    public function somethingWentWrong(string $message): Response
    {
        return response($message, 500);
    }

    public function balanceNotEnough(): JsonResponse
    {
        return response()->json([
            'errorCode' => 402,
            'errorMessage' => 'Insufficient balance',
        ]);
    }
    public function maxWinningExceed(): JsonResponse
    {
        return response()->json([
            'errorCode' => 406,
            'errorMessage' => 'Over the max winning',
        ]);
    }
    public function betLimitExceed(): JsonResponse
    {
        return response()->json([
            'errorCode' => 407,
            'errorMessage' => 'Over the max loss',
        ]);
    }

    public function betAlreadySettled(): JsonResponse
    {
        return response()->json([
            'errorCode' => 409,
            'errorMessage' => 'Bet was already settled',
        ]);
    }

    public function betAlreadyCancelled(): JsonResponse
    {
        return response()->json([
            'errorCode' => 410,
            'errorMessage' => 'Bet was already cancelled',
        ]);
    }
}