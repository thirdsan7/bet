<?php
namespace App\Libraries;

use App\Exceptions\General\InvalidInputException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LaravelLib
{    
    /**
     * returns random string with given length
     *
     * @param  int $length
     * @return string
     */
    public function randomString(int $length): string
    {
        return Str::random($length);
    }
    
    /**
     * validates request based on rules given
     *
     * @param  Request $request
     * @param  array $rules
     * @return void
     * @throws InvalidInputException
     */
    public function validate(Request $request, array $rules): void
    {
        $validation = Validator::make($request->all(), $rules);

        if($validation->fails())
            throw new InvalidInputException($validation->errors());
    }
}