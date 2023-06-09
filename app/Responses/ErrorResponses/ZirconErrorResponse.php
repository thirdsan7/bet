<?php
namespace App\Responses\ErrorResponses;

use App\Responses\ErrorResponses\IErrorResponse;
use Illuminate\Http\JsonResponse;

class ZirconErrorResponse implements IErrorResponse
{
    /**
     * formattez zircon response for invalidInput
     *
     * @param  string $errors
     * @return JsonResponse
     */
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
    
    /**
     * formatted zircon response for invalidGameID
     *
     * @return JsonResponse
     */
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
    
    /**
     * formatted zircon response for playerNotLoggedIn
     *
     * @return JsonResponse
     */
    public function playerNotLoggedIn(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 1,
                'message' => 'Session expired'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for systemUnderMaintenance 
     *
     * @return JsonResponse
     */
    public function systemUnderMaintenance(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 201,
                'message' => 'System under maintenance'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for betAlreadyExists
     *
     * @return JsonResponse
     */
    public function betAlreadyExists(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 101,
                'message' => 'RoundDetID already used'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for somethingWentWrong
     *
     * @param  string $message
     * @return JsonResponse
     */
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
    
    /**
     * formatted zircon response for balanceNotEnough
     *
     * @return JsonResponse
     */
    public function balanceNotEnough(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 4,
                'message' => 'Not enough balance'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for maxWinningExceed
     *
     * @return JsonResponse
     */
    public function maxWinningExceed(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 5,
                'message' => 'Betting limit exceed'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for betLimitExceed
     *
     * @return JsonResponse
     */
    public function betLimitExceed(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 5,
                'message' => 'Betting limit exceed'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for betAlreadySettled
     *
     * @return JsonResponse
     */
    public function betAlreadySettled(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 102,
                'message' => 'RoundDetID already settled'
            ]
        ]);
    }
    
    /**
     * formatted zircon response for betAlreadyCancelled
     *
     * @return JsonResponse
     */
    public function betAlreadyCancelled(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 104,
                'message' => 'RoundDetID already cancelled'
            ]
        ]);
    }

    public function betNotFound(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 103,
                'message' => 'RoundDetID not found'
            ]
        ]);
    }
}