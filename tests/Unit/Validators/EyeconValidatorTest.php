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
            'uid' => 1, 
            'guid' => 1,
            'accessid' => 'accessid',
            'type' => 'BET',
            'round' => 20,
            'gameid' => 1,
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, [
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
            ]);

        $validator = $this->makeValidator($mockLib);
        $validator->validate($request);
    }

    public function test_validate_invalidAccessID_exception()
    {
        $request = new Request([
            'uid' => 1, 
            'guid' => 1,
            'accessid' => 'invalid_accessid', //invalid accessID
            'type' => 'BET',
            'round' => 20,
            'gameid' => 1,
            'ref' => 'ref1',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'status' => 'active',
            'wager' => 100,
            'win' => 'win',
            'jpwin' => 'jpwin'
        ]);

        $this->expectException(InvalidInputException::class);

        $validator = $this->makeValidator();
        $validator->validate($request);
    }
}