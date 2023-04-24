<?php
namespace App\Responses\ErrorResponses;

use Illuminate\Http\Response;
use App\Responses\ErrorResponses\IErrorResponse;

class EyeconErrorResponse implements IErrorResponse
{
    /**
     * formatted eyecon response for invalidInput
     *
     * @param  mixed $message
     * @return Response
     */
    public function invalidInput(string $message): Response
    {
        return response('', 500);
    }
    
    /**
     * formatted eyecon response for invalidGameID
     *
     * @return Response
     */
    public function invalidGameID(): Response
    {
        return response('status=invalid&error=3');
    }
    
    /**
     * formatted eyecon response for playerNotLoggedIn
     *
     * @return Response
     */
    public function playerNotLoggedIn(): Response
    {
        return response('status=invalid&error=12');
    }
    
    /**
     * formatted eyecon response for systemUnderMaintenance
     *
     * @return Response
     */
    public function systemUnderMaintenance(): Response
    {
        return response('', 503);
    }
    
    /**
     * formatted eyecon response for betAlreadyExists
     *
     * @return Response
     */
    public function betAlreadyExists(): Response
    {
        return response('status=invalid&error=15');
    }
    
    /**
     * formatted eyecon response for somethingWentWrong
     *
     * @param  string $message
     * @return Response
     */
    public function somethingWentWrong(string $message): Response
    {
        return response($message, 500);
    }
    
    /**
     * formatted eyecon response for balanceNotEnough
     *
     * @return Response
     */
    public function balanceNotEnough(): Response
    {
        return response('status=invalid&error=13');
    }
    
    /**
     * formatted eyecon response for maxWinningExceed
     *
     * @return Response
     */
    public function maxWinningExceed(): Response
    {
        return response('status=invalid&error=21');
    }
    
    /**
     * formatted eyecon response for betLimitExceed
     *
     * @return Response
     */
    public function betLimitExceed(): Response
    {
        return response('status=invalid&error=21');
    }
    
    /**
     * formatted eyecon response for betAlreadySettled
     *
     * @return Response
     */
    public function betAlreadySettled(): Response
    {
        return response('status=invalid&error=15');
    }
    
    /**
     * formatted eyecon response for betAlreadyCancelled
     *
     * @return Response
     */
    public function betAlreadyCancelled(): Response
    {
        return response('status=invalid&error=16');
    }

    public function betNotFound(): Response
    {
        return response('Bet was not found', 500);
    }
}