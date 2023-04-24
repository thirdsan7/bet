<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SettleBetTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=ResultBetSeeder');
    }

    public function test_resultBet_validData_expectedResponse()
    {
        $request = [
            'refNo' => 'running_bet',
            'betResultReq' => [
                'gameCode' => 1,
                'playerId' => 1,
                'winAmount' => 100,
                'effectiveStake' => 50,
                'stake' => 100
            ]
        ];

        $lcApiResponse = json_encode([
            'errorCode' => 0,
            'errorMessage' => 'Success',
            'data' => [
                'transactionId' => 'TransactionId',
                'balance' => 1000,
                'playerId' => 'PlayerId',
                'statementDate' => 'StatementDate',
            ]
        ]);

        Http::fake([
            '*' => Http::response($lcApiResponse, 200)
        ]);

        $headers = [
            'Authentication' => env('FUNKY_ZIRCON_TOKEN')
        ];

        $this->json('POST', '/Funky/Bet/SettleBet', $request, $headers)
            ->seeJson([
                'errorCode' => 0,
                'errorMessage' => 'NoError',    
                'data' => [
                    'refNo' => 'running_bet',
                    'balance' => 1000,
                    'playerId' => 1,
                    'currency' => '',
                    'statementDate' => 'StatementDate'
                ]
            ]);
    }
}
