<?php

use Tests\TestCase;
use App\Entities\ZirconBet;
use App\Models\Transaction;
use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Repositories\TransactionRepository;
use App\Exceptions\Transaction\RoundNotFoundException;

class ZirconBetTest extends TestCase
{
    public function makeBet($repo = null) 
    {
        $repo ??= $this->createStub(TransactionRepository::class);

        return new ZirconBet($repo);
    }

    public function test_new_mockRepo_getBySboClientIDGameIDRoundDetID()
    {
        $player = $this->createStub(IPlayer::class);
        $player->method('getClientID')
            ->willReturn(1);

        $player->method('getSessionID')
            ->willReturn('sessionID');

        $game = $this->createStub(IGame::class);
        $game->method('getGameID')
            ->willReturn(2);

        $roundDetID = 'roundDetID';
        $stake = 10.0;
        $ip = 'ip';

        $mockRepo = $this->createMock(TransactionRepository::class);
        $mockRepo->expects($this->once())
            ->method('getBySboClientIDGameIDRoundDetID')
            ->with(1, 2, $roundDetID);

        $bet = $this->makeBet($mockRepo);
        $bet->new($player, $game, $roundDetID, $stake, $ip);
    }

    public function test_new_stubRepoNotEmpty_roundAlreadyExistsException()
    {
        $player = $this->createStub(IPlayer::class);
        $game = $this->createStub(IGame::class);

        $roundDetID = 'roundDetID';
        $stake = 10.0;
        $ip = 'ip';

        $this->expectException(RoundAlreadyExistsException::class);

        $stubRepo = $this->createStub(TransactionRepository::class);
        $stubRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(Transaction::factory()->make([
                'roundDetID' => 'roundDetID'
            ]));

        $bet = $this->makeBet($stubRepo);
        $bet->new($player, $game, $roundDetID, $stake, $ip);
    }

    public function test_new_mockGame_getGameID()
    {
        $mockGame = $this->createMock(IGame::class);
        $mockGame->expects($this->exactly(2))
            ->method('getGameID')
            ->willReturn(2);

        $player = $this->createStub(IPlayer::class);
        $player->method('getClientID')
            ->willReturn(1);

        $player->method('getSessionID')
            ->willReturn('sessionID');

        $roundDetID = 'roundDetID';
        $stake = 10.0;
        $ip = 'ip';

        $stubRepo = $this->createStub(TransactionRepository::class);
        $stubRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(null);

        $bet = $this->makeBet($stubRepo);
        $bet->new($player, $mockGame, $roundDetID, $stake, $ip);
    }

    public function test_new_mockPlayer_getClientID()
    {
        $mockPlayer = $this->createMock(IPlayer::class);
        $mockPlayer->expects($this->exactly(2))
            ->method('getClientID')
            ->willReturn(1);

        $mockPlayer->method('getSessionID')
            ->willReturn('sessionID');

        $game = $this->createStub(IGame::class);
        $game->method('getGameID')
            ->willReturn(2);

        $roundDetID = 'roundDetID';
        $stake = 10.0;
        $ip = 'ip';

        $stubRepo = $this->createStub(TransactionRepository::class);
        $stubRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(null);

        $bet = $this->makeBet($stubRepo);
        $bet->new($mockPlayer, $game, $roundDetID, $stake, $ip);
    }

    public function test_new_mockPlayer_getSessionID()
    {
        $mockPlayer = $this->createMock(IPlayer::class);
        $mockPlayer->expects($this->once())
            ->method('getSessionID')
            ->willReturn('sessionID');

        $mockPlayer->method('getClientID')
            ->willReturn(1);

        $game = $this->createStub(IGame::class);
        $game->method('getGameID')
            ->willReturn(2);

        $roundDetID = 'roundDetID';
        $stake = 10.0;
        $ip = 'ip';

        $stubRepo = $this->createStub(TransactionRepository::class);
        $stubRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(null);

        $bet = $this->makeBet($stubRepo);
        $bet->new($mockPlayer, $game, $roundDetID, $stake, $ip);
    }

    public function test_create_mockRepo_create()
    {
        $player = $this->createStub(IPlayer::class);
        $player->method('getClientID')
            ->willReturn(1);

        $player->method('getSessionID')
            ->willReturn('sessionID');

        $game = $this->createStub(IGame::class);
        $game->method('getGameID')
            ->willReturn(2);

        $mockRepo = $this->createMock(TransactionRepository::class);
        $mockRepo->expects($this->once())
            ->method('create')
            ->with('roundDetID', 1, 'sessionID', 2, 10.0, 'roundDetID-2-'.env('ENV_ID'));

        $bet = $this->makeBet($mockRepo);
        $bet->new($player, $game, 'roundDetID', 10.0, 'ip');

        $bet->create();
    }

    public function test_getRefNo_givenData_expected()
    {
        $player = $this->createStub(IPlayer::class);
        $player->method('getClientID')
            ->willReturn(1);

        $player->method('getSessionID')
            ->willReturn('sessionID');

        $game = $this->createStub(IGame::class);
        $game->method('getGameID')
            ->willReturn(1);

        $bet = $this->makeBet();
        $bet->new($player, $game, 'roundDetID', 10.0, 'ip');

        $result = $bet->getRefNo($game);

        $this->assertSame('roundDetID-1-'.env('ENV_ID'), $result);
    }

    public function test_init_mockRepo_getBySboClientIDGameIDRoundDetID()
    {
        $player = $this->createStub(IPlayer::class);
        $player->method('getClientID')
            ->willReturn(1);

        $game = $this->createStub(IGame::class);
        $game->method('getGameID')
            ->willReturn(2);

        $roundDetID = 'roundDetID';
        $totalWin = 10.0;
        $turnover = 5.0;

        $mockRepo = $this->createMock(TransactionRepository::class);
        $mockRepo->expects($this->once())
            ->method('getBySboClientIDGameIDRoundDetID')
            ->with(1, 2, 'roundDetID')
            ->willReturn(Transaction::factory()->make([
                'stake' => 10.0
            ]));

        $bet = $this->makeBet($mockRepo);
        $bet->init($player, $game, $roundDetID, $totalWin, $turnover);
    }

    public function test_init_stubRepoEmptyReturn_RoundNotFoundException()
    {
        $player = $this->createStub(IPlayer::class);
        $game = $this->createStub(IGame::class);
        $roundDetID = 'roundDetID';
        $totalWin = 10.0;
        $turnover = 5.0;

        $this->expectException(RoundNotFoundException::class);

        $stubRepo = $this->createStub(TransactionRepository::class);
        $stubRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(null);

        $bet = $this->makeBet($stubRepo);
        $bet->init($player, $game, $roundDetID, $totalWin, $turnover);
    }

    public function test_settle_mockRepoTotalWin0_updateByTransactionID()
    {
        $player = $this->createStub(IPlayer::class);
        $game = $this->createStub(IGame::class);
        $roundDetID = 'roundDetID';
        $totalWin = 0;
        $turnover = 5.0;

        $mockRepo = $this->createMock(TransactionRepository::class);
        $mockRepo->expects($this->once())
            ->method('updateByTransactionID')
            ->with([
                'totalWin' => 0,
                'turnover' => 5.0,
                'event' => 'L'
            ], 1);

        $mockRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(Transaction::factory()->make([
                'stake' => 10.0,
                'transactionCWID' => 1
            ]));

        $bet = $this->makeBet($mockRepo);
        $bet->init($player, $game, $roundDetID, $totalWin, $turnover);

        $bet->settle();
    }

    public function test_settle_mockRepoTotalWin10_updateByTransactionID()
    {
        $player = $this->createStub(IPlayer::class);
        $game = $this->createStub(IGame::class);
        $roundDetID = 'roundDetID';
        $totalWin = 10.0;
        $turnover = 5.0;

        $mockRepo = $this->createMock(TransactionRepository::class);
        $mockRepo->expects($this->once())
            ->method('updateByTransactionID')
            ->with([
                'totalWin' => 10.0,
                'turnover' => 5.0,
                'event' => 'W'
            ], 1);

        $mockRepo->method('getBySboClientIDGameIDRoundDetID')
            ->willReturn(Transaction::factory()->make([
                'stake' => 10.0,
                'transactionCWID' => 1
            ]));

        $bet = $this->makeBet($mockRepo);
        $bet->init($player, $game, $roundDetID, $totalWin, $turnover);

        $bet->settle();
    }
}