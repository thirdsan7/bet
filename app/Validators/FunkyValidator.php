<?php
namespace App\Validators;

use App\Validators\ZirconValidator;

class FunkyValidator extends ZirconValidator
{
    protected $sellBetRules = [
        'bet.gameCode' => 'required',
        'bet.refNo' => 'required',
        'bet.stake' => 'required',
        'sessionId' => 'required',
        'playerIp' => 'required'
    ];
}