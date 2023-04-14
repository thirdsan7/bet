<?php

use App\Exceptions\General\InvalidInputException;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Libraries\LaravelLib;
use App\Validators\FunkyValidator;

class FunkyValidatorTest extends TestCase
{
    public function makeValidator($lib = null) 
    {
        $lib ??= $this->createStub(LaravelLib::class);

        return new FunkyValidator($lib);
    }

    public function test_validateSellBet_mockLib_validate()
    {
        $request = new Request([
            'bet' => [
                'gameCode' => 1,
                'refNo' => 10,
                'stake' => 100
            ],
            'sessionId' => 1,
            'playerIp' => 'playerIp'
        ]);

        $mockLib = $this->createMock(LaravelLib::class);
        $mockLib->expects($this->once())
            ->method('validate')
            ->with($request, [
                'bet.gameCode' => 'required',
                'bet.refNo' => 'required',
                'bet.stake' => 'required',
                'sessionId' => 'required',
                'playerIp' => 'required'
            ]);

        $validator = $this->makeValidator($mockLib);
        $validator->validateSellBet($request);  
    }
}