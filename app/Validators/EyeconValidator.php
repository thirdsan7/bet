<?php
namespace App\Validators;

use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Validators\Validator;
use App\Exceptions\General\InvalidInputException;

class EyeconValidator extends Validator
{
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
     * validates request if correct format, data type and validates accessid if correct
     *
     * @param  Request $request
     * @return void
     * @throws InvalidInputException
     */
    public function validate(Request $request, array $rules)
    {
        parent::validate($request, $rules);

        $this->validateAccessID($request->accessid);
    }
}