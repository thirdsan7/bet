<?php
namespace App\Responses;

use App\Entities\Interfaces\IBet;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use Illuminate\Http\JsonResponse;

class ZirconResponse
{
const RUNNING_EVENT = 'R';
    public function sellBet(IPlayer $player, IGame $game, IBet $bet): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 0,
                'message' => 'Success'
            ],
            'data' => [
                'roundDetID' => $bet->getRoundDetID(),
                'gameID' => $game->getGameID(),
                'event' => self::RUNNING_EVENT,
                'balance' => $player->getBalance(),
            ]
        ]);
    }

    public function invalidInput(string $errors): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => -1,
                'message' => 'Invalid data given',
                'details' => $errors
            ]
        ]);
    }

    public function invalidGameID(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => -1,
                'message' => 'Invalid data given',
                'details' => 'gameID not found'
            ]
        ]);
    }

    public function playerNotLoggedIn(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 1,
                'message' => 'Session expired'
            ]
        ]);
    }

    public function systemUnderMaintenance(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 201,
                'message' => 'System under maintenance'
            ]
        ]);
    }

    public function betAlreadyExists(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 101,
                'message' => 'RoundDetID already used'
            ]
        ]);
    }

    public function somethingWentWrong(string $message): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 500,
                'message' => 'Something went wrong',
                'details' => $message
            ]
        ]);
    }

    public function balanceNotEnough(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 4,
                'message' => 'Not enough balance'
            ]
        ]);
    }

    public function maxWinningExceed(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 5,
                'message' => 'Betting limit exceed'
            ]
        ]);
    }

    public function betLimitExceed(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 5,
                'message' => 'Betting limit exceed'
            ]
        ]);
    }

    public function betAlreadySettled(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 102,
                'message' => 'RoundDetID already settled'
            ]
        ]);
    }

    public function betAlreadyCancelled(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 104,
                'message' => 'TransactionDetID already cancelled'
            ]
        ]);
    }

    
}