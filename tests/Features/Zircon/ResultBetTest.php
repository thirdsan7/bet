<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ResultBetTest extends TestCase
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
            'roundDetID' => 'running_bet',
            'gameID' => 1,
            'clientID' => 1,
            'totalWin' => 100,
            'turnover' => 100
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

        $this->json('POST', 'resultbet', $request)
            ->seeJson([
                'data' => [
                    'roundDetID' => 10,
                    'gameID' => 10001,
                    'balance' => 1000
                ]
            ]);
    }
}