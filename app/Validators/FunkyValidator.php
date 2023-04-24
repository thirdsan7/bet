<?php
namespace App\Validators;

use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Validators\Validator;
use App\Exceptions\General\InvalidInputException;

class FunkyValidator extends Validator
{
    protected $lib;

    public function __construct(LaravelLib $lib)
    {
        $this->lib = $lib;
    }

    private function validateAuthenticationHeader(Request $request)
    {
        if (!$request->hasHeader('Authentication') || 
            $request->header('Authentication') != config('zircon.FUNKY_ZIRCON_TOKEN')) {
                throw new InvalidInputException;
        }
    }

    /**
     * validates sell bet reqyest if correct format and data type
     *
     * @param  Request $request
     * @param  array $rules
     * @return void
     * @throws InvalidInputException
     */
    public function validate(Request $request, array $rules)
    {
        parent::validate($request, $rules);

        $this->validateAuthenticationHeader($request);
    }
}