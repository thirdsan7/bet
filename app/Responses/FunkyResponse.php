<?php
namespace App\Responses;

use Illuminate\Http\JsonResponse;
use App\Entities\Interfaces\IPlayer;
use Illuminate\Http\Response;

class FunkyResponse
{    
    /**
     * formatted funky response for placeBet
     *
     * @param  IPlayer $player
     * @return JsonResponse
     */
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
    
    /**
     * formatted funky response for invalidInput
     *
     * @param  string $message
     * @return JsonResponse
     */
    public function invalidInput(string $message): JsonResponse
    {
        return response()->json([
            'errorCode' => 400,
            'errorMessage' => 'Invalid input',
        ]);
    }
    
    /**
     * formatted funky response for invalidGameID
     *
     * @return JsonResponse
     */
    public function invalidGameID(): JsonResponse
    {
        return response()->json([
            'errorCode' => 400,
            'errorMessage' => 'Invalid input',
        ]);
    }
    
    /**
     * formatted funky response for playerNotLoggedIn
     *
     * @return JsonResponse
     */
    public function playerNotLoggedIn(): JsonResponse
    {
        return response()->json([
            'errorCode' => 401,
            'errorMessage' => 'Player is not login',
        ]);
    }
    
    /**
     * formatted funky response for systemUnderMaintenance
     *
     * @return JsonResponse
     */
    public function systemUnderMaintenance(): JsonResponse
    {
        return response()->json([
            'errorCode' => 405,
            'errorMessage' => 'API suspended',
        ]);
    }
    
    /**
     * formatted funky response for betAlreadyExists
     *
     * @return JsonResponse
     */
    public function betAlreadyExists(): JsonResponse
    {
        return response()->json([
            'errorCode' => 403,
            'errorMessage' => 'Bet already exists',
        ]);
    }
    
    /**
     * formatted funky response for somethingWentWrong
     *
     * @param  mixed $message
     * @return Response
     */
    public function somethingWentWrong(string $message): Response
    {
        return response($message, 500);
    }
    
    /**
     * formatted funky response for balanceNotEnough
     *
     * @return JsonResponse
     */
    public function balanceNotEnough(): JsonResponse
    {
        return response()->json([
            'errorCode' => 402,
            'errorMessage' => 'Insufficient balance',
        ]);
    }    
    /**
     * formatted funky response for maxWinningExceed
     *
     * @return JsonResponse
     */
    public function maxWinningExceed(): JsonResponse
    {
        return response()->json([
            'errorCode' => 406,
            'errorMessage' => 'Over the max winning',
        ]);
    }    
    /**
     * formatted funky response for betLimitExceed
     *
     * @return JsonResponse
     */
    public function betLimitExceed(): JsonResponse
    {
        return response()->json([
            'errorCode' => 407,
            'errorMessage' => 'Over the max loss',
        ]);
    }
    
    /**
     * formatted funky response for betAlreadySettled
     *
     * @return JsonResponse
     */
    public function betAlreadySettled(): JsonResponse
    {
        return response()->json([
            'errorCode' => 409,
            'errorMessage' => 'Bet was already settled',
        ]);
    }
    
    /**
     * formatted funky response for betAlreadyCancelled
     *
     * @return JsonResponse
     */
    public function betAlreadyCancelled(): JsonResponse
    {
        return response()->json([
            'errorCode' => 410,
            'errorMessage' => 'Bet was already cancelled',
        ]);
    }
}