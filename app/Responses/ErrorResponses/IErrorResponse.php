<?php
namespace App\Responses\ErrorResponses;

use Illuminate\Http\JsonResponse;

interface IErrorResponse
{
    public function invalidInput(string $message);
    public function invalidGameID();
    public function playerNotLoggedIn();
    public function systemUnderMaintenance();
    public function betAlreadyExists();
    public function somethingWentWrong(string $message);
    public function balanceNotEnough();
    public function maxWinningExceed();
    public function betLimitExceed();
    public function betAlreadySettled();
    public function betAlreadyCancelled();
    public function betNotFound();
}