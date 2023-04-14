<?php

use App\Exceptions\General\InvalidInputException;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Validators\EyeconValidator;

class EyeconValidatorTest extends TestCase
{
    public function makeValidator($lib = null) 
    {
        $lib ??= $this->createStub(LaravelLib::class);

        return new EyeconValidator($lib);
    }

    public function test_validate_mockLib_validate()
    {
        $request = new Request([
            'accessid' => 'accessid',
        ]);

        $rules = [];

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, $rules);

        $validator = $this->makeValidator($mockLib);
        $validator->validate($request, $rules);
    }

    public function test_validate_invalidAccessID_exception()
    {
        $request = new Request([
            'accessid' => 'invalid_accessid', //invalid accessID
        ]);

        $rules = [];

        $this->expectException(InvalidInputException::class);

        $validator = $this->makeValidator();
        $validator->validate($request, $rules);
    }
}