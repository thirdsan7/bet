<?php
namespace App\Validators;

use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Exceptions\General\InvalidInputException;

class Validator
{
    protected $lib;

    public function __construct(LaravelLib $lib)
    {
        $this->lib = $lib;
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
        $this->lib->validate($request, $rules);
    }
}