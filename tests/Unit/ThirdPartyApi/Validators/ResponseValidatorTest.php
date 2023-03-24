<?php

use Tests\TestCase;
use Illuminate\Http\Client\Response;
use App\Exceptions\Player\BetLimitException;
use App\Exceptions\ThirdParty\ThirdPartyException;
use App\Exceptions\Player\MaxWinningLimitException;
use App\ThirdPartyApi\Validators\ResponseValidator;
use App\Exceptions\Player\BalanceNotEnoughException;
use App\Exceptions\Player\PlayerNotLoggedInException;
use App\Exceptions\Transaction\RoundNotFoundException;
use App\Exceptions\Game\SystemUnderMaintenanceException;
use App\Exceptions\Transaction\RoundAlreadyCancelledException;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Exceptions\Transaction\RoundAlreadySettledException;

class ResponseValidatorTest extends TestCase
{
    public function makeValidator()
    {
        return new ResponseValidator;
    }

    public function test_validate_statusNot200_ThirdPartyException()
    {
        $response = $this->createStub(Response::class);
        $response->method('status')
            ->willReturn(500);

        $this->expectException(ThirdPartyException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseBodyNotJson_ThirdPartyException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn('response');

        $response->method('status')
            ->willReturn(200);

        $this->expectException(ThirdPartyException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode401_PlayerNotLoggedInException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 401
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(PlayerNotLoggedInException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }
    
    public function test_validate_responseErrorCode402_BalanceNotEnoughException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 402
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(BalanceNotEnoughException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode403_RoundAlreadyExistsException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 403
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(RoundAlreadyExistsException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode404_RoundNotFoundException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 404
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(RoundNotFoundException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode405_SystemUnderMaintenanceException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 405
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(SystemUnderMaintenanceException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode406_MaxWinningLimitException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 406
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(MaxWinningLimitException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode407_BetLimitException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 407
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(BetLimitException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode409_RoundAlreadySettledException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 409
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(RoundAlreadySettledException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode410_RoundAlreadycancelledException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 410
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(RoundAlreadyCancelledException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCodeUnknown_ThirdPartyException()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => -123
            ]));

        $response->method('status')
            ->willReturn(200);

        $this->expectException(ThirdPartyException::class);

        $validator = $this->makeValidator();

        $validator->validate($response);
    }

    public function test_validate_responseErrorCode0_void()
    {
        $response = $this->createStub(Response::class);
        $response->method('body')
            ->willReturn(json_encode([
                'errorCode' => 0
            ]));

        $response->method('status')
            ->willReturn(200);

        $validator = $this->makeValidator();

        $result = $validator->validate($response);

        $this->assertNull($result);
    }
}