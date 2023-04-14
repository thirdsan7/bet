<?php

use App\Exceptions\General\InvalidInputException;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Validators\EyeconValidator;
use App\Validators\ZirconValidator;

class ZirconValidatorTest extends TestCase
{
    public function makeValidator($lib = null) 
    {
        $lib ??= $this->createStub(LaravelLib::class);

        return new ZirconValidator($lib);
    }

    public function test_validateSellBet_mockLib_validate()
    {
        $request = new Request([
            'stake' => 100,
            'roundDetID' => 'roundDetID',
            'roundID' => 'roundID',
            'gameID' => 1,
            'clientID' => 1,
            'sessionID' => 1,
            'ip' => 'ip'
        ]);

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, [
                'stake' => 'required',
                'roundDetID' => 'required',
                'roundID' => 'required',
                'gameID' => 'required',
                'clientID' => 'required',
                'sessionID' => 'required',
                'ip' => 'required'
            ]);

        $validator = $this->makeValidator($mockLib);
        $validator->validateSellBet($request);
    }
}