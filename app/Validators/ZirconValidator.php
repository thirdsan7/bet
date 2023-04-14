<?php
namespace App\Validators;

use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Exceptions\General\InvalidInputException;

class ZirconValidator
{
    protected $sellBetRules = [
        'stake' => 'required',
        'roundDetID' => 'required',
        'roundID' => 'required',
        'gameID' => 'required',
        'clientID' => 'required',
        'sessionID' => 'required',
        'ip' => 'required'
    ];

    protected $lib;

    public function __construct(LaravelLib $lib)
    {
        $this->lib = $lib;
    }

    /**
     * validates sell bet reqyest if correct format and data type
     *
     * @param  Request $request
     * @return void
     * @throws InvalidInputException
     */
    public function validateSellBet(Request $request)
    {
        $this->lib->validate($request, $this->sellBetRules);
    }
}