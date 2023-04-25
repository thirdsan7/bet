<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class BetCompleteTest extends TestCase
{
    static function setUpBeforeClass(): void
    {
        exec('php artisan migrate:fresh');
        exec('php artisan db:seed');
        exec('php artisan db:seed --class=ResultBetSeeder');
    }
    
    public function test_settleBetComplete_WithoutActiveRoundTotalWinLCApiResponse1000_balance1000()
    {
        $request = [
            'uid' => 1,
            'round' => 'running_bet',
            'gameid' => 1,
            'ref' => 'ref',
            'win' => 100,
            'type' => 'WIN',
            'gtype' => 'gtype',
            'cur' => 'cur',
            'wager' => 100,
            'guid' => 1,
            'accessid' => 'accessid',
            'jpwin' => 'jpwin',
            'status' => 'complete'
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

        $response = $this->call('GET', '/api/eyecon', $request);

        $this->assertSame('status=ok&bal=1000', $response->original);
    }
}