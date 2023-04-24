<?php

use Tests\TestCase;
use App\Libraries\LaravelLib;
use Laravel\Lumen\Http\Request;
use App\Validators\FunkyValidator;
use App\Exceptions\General\InvalidInputException;

class FunkyValidatorTest extends TestCase
{
    public function makeValidator($lib = null) 
    {
        $lib ??= $this->createStub(LaravelLib::class);

        return new FunkyValidator($lib);
    }

    public function test_validate_mockLib_validate()
    {
        $request = new Request();
        $request->headers->set('Authentication', env('FUNKY_ZIRCON_TOKEN'));

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
        $request = new Request();
        $request->header('Authentication', 'test');

        $rules = [];

        $this->expectException(InvalidInputException::class);

        $validator = $this->makeValidator();
        $validator->validate($request, $rules);
    }
}