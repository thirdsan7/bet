<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Services\Interfaces\IBet;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ITransactionRepository;

class TransactionRepository implements ITransactionRepository
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getByRoundDetIDPlayerIDGameID($roundDetID, $clientID, $gameID): Transaction
    {
        return $this->transaction->select('*')
            ->where('roundDetID', '=', $roundDetID)
            ->where('sboClientID', '=', $clientID)
            ->where('gameID', '=', $gameID)
            ->get()
            ->first();
    }

    public function create($roundDetID, $sboClientID, $sessionID, $gameID, $stake, $refNo): void
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

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollBack()
    {
        DB::rollBack();
    }
}