<?php
namespace App\Libraries;

use App\Exceptions\General\InvalidInputException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LaravelLib
{
    public function randomString($length)
    {
        return Str::random($length);
    }

    public function validate(Request $request, array $rules)
    {
        $validation = Validator::make($request->all(), $rules);

        if($validation->fails())
            throw new InvalidInputException($validation->errors());
    }
}