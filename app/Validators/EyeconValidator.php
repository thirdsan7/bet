<?php
namespace App\Validators;

use App\Exceptions\General\InvalidInputException;
use App\Libraries\LaravelLib;
use Illuminate\Http\Request;

class EyeconValidator
{
    const EYECON_REQUEST = [
        'uid' => 'required',
        'guid' => 'required',
        'accessid' => 'required',
        'type' => 'required',
        'round' => 'required',
        'gameid' => 'required',
        'ref' => 'required',
        'gtype' => 'required',
        'cur' => 'required',
        'status' => 'required',
        'wager' => 'required',
        'win' => 'required',
        'jpwin' => 'required'
    ];

    private $lib;

    public function __construct(LaravelLib $lib)
    {
        $this->lib = $lib;
    }
    
    /**
     * validates accessid
     *
     * @param  string $accessID
     * @return void
     * @throws InvalidInputException
     */
    private function validateAccessID(string $accessID)
    {
        if ($accessID !== config('zircon.EYECON_ACCESS_ID'))
            throw new InvalidInputException('Invalid AccessID');
    }

        
    /**
     * validates request if correct format and validates accessid if correct
     *
     * @param  Request $request
     * @return void
     * @throws InvalidInputException
     */
    public function validate(Request $request)
    {
        $this->lib->validate($request, self::EYECON_REQUEST);

        $this->validateAccessID($request->accessid);
    }
}