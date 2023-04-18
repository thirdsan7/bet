<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Services\Interfaces\IBet;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ITransactionRepository;
use Illuminate\Database\QueryException;

class TransactionRepository implements ITransactionRepository
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
    
    /**
     * inserts data into database by values given
     *
     * @param  string $roundDetID
     * @param  int $sboClientID
     * @param  string $sessionID
     * @param  int $gameID
     * @param  float $stake
     * @param  string $refNo
     * @return void
     * @throws QueryException
     */
    public function create(string $roundDetID, int $sboClientID, string $sessionID, int $gameID, float $stake, string $refNo): void
    {
        $this->transaction->create([
            'roundDetID' => $roundDetID,
            'sboClientID' => $sboClientID,
            'sessionID' => $sessionID,
            'gameID' => $gameID,
            'stake' => $stake,
            'event' => 'R',
            'refNo' => $refNo
        ]);
    }

    public function getBySboClientIDGameIDRoundDetID(int $sboClientID, int $gameID, string $roundDetID): Transaction|null
    {
        
    }

    public function updateByTransactionID(array $updates, int $transactionID): void
    {

    }
}