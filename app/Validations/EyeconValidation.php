<?php
namespace App\Validations;

use App\Exceptions\General\InvalidInputException;
use App\Libraries\LaravelLib;
use Illuminate\Http\Request;

class EyeconValidation
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

    private function validateAccessID(string $accessID)
    {
        if($accessID !== config('zircon.EYECON_ACCESS_ID')) 
            throw new InvalidInputException('Invalid AccessID');
    }

    public function validate(Request $request)
    {
        $this->lib->validate($request, self::EYECON_REQUEST);

        $this->validateAccessID($request->accessid);
    }
}