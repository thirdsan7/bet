<?php

use App\Entities\Interfaces\IGame;
use App\Entities\Interfaces\IPlayer;
use App\Entities\ZirconBet;
use App\Repositories\TransactionRepository;
use Tests\TestCase;

class ZirconBetTest extends TestCase
{
    public function makeBet($repo = null) 
    {
        $repo ??= $this->createStub(TransactionRepository::class);

        return new ZirconBet($repo);
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
            ->willReturn(1);

        $mockRepo = $this->createMock(TransactionRepository::class);
        $mockRepo->expects($this->once())
            ->method('create')
            ->with('roundDetID', 1, 'sessionID', 1, 10.0, 'roundDetID-1-'.env('ENV_ID'));

        $bet = $this->makeBet($mockRepo);
        $bet->new('roundDetID', 10.0, 'ip');

        $bet->create($player, $game);
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
        $bet->new('roundDetID', 10.0, 'ip');

        $result = $bet->getRefNo($game);

        $this->assertSame('roundDetID-1-'.env('ENV_ID'), $result);
    }
}