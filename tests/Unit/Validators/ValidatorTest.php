<?php

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Validators\Validator;

class ValidatorTest extends TestCase
{
    public function makeValidator($lib = null) 
    {
        $lib ??= $this->createStub(LaravelLib::class);

        return new Validator($lib);
    }

    public function test_validate_mockLib_validate()
    {
        $request = new Request([]);

        $rules = [];

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, $rules);

        $validator = $this->makeValidator($mockLib);
        $validator->validate($request, $rules);
    }
}