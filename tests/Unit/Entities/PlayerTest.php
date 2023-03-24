<?php

use App\Entities\Interfaces\IGame;
use App\Entities\Player;
use App\Exceptions\Player\PlayerNotLoggedInException;
use App\Models\LoginInfo;
use App\Repositories\PlayerRepository;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    public function makePlayer($repo = null)
    {
        $repo ??= $this->createStub(PlayerRepository::class);
        
        return new Player($repo);
    }

    public function test_initBySessionIDGameID_mockGame_getGameID()
    {
        $sessionID = 1;

        $mockGame = $this->createMock(IGame::class);
        $mockGame->expects($this->once())
            ->method('getGameID');

        $stubRepo = $this->createStub(PlayerRepository::class);
        $stubRepo->method('getBySessionIDGameID')
            ->willReturn(LoginInfo::factory()->make([
                'sboClientID' => 1,
                'sessionID' => 1,
                'loginIP' => 'loginIP',
                'isTestPlayer' => 0
            ]));

        $player = $this->makePlayer($stubRepo);

        $player->initBySessionIDGameID($sessionID, $mockGame);
    }

    public function test_initBySessionIDGameID_mockRepo_getBySessionIDGameID()
    {
        $sessionID = 1;

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('getGameID')
            ->willReturn(1);

        $mockRepo = $this->createMock(PlayerRepository::class);
        $mockRepo->expects($this->once())
            ->method('getBySessionIDGameID')
            ->with($sessionID, 1)
            ->willReturn(LoginInfo::factory()->make([
                'sboClientID' => 1,
                'sessionID' => 1,
                'loginIP' => 'loginIP',
                'isTestPlayer' => 0
            ]));

        $player = $this->makePlayer($mockRepo);

        $player->initBySessionIDGameID($sessionID, $stubGame);
    }

    public function test_initBySessionIDGameID_stubRepoReturnsEmpty_PlayerNotLoggedInException()
    {
        $sessionID = 1;

        $stubGame = $this->createStub(IGame::class);
        $stubGame->method('getGameID')
            ->willReturn(1);

        $stubRepo = $this->createStub(PlayerRepository::class);
        $stubRepo->method('getBySessionIDGameID')
            ->willReturn(null);

        $this->expectException(PlayerNotLoggedInException::class);

        $player = $this->makePlayer($stubRepo);

        $player->initBySessionIDGameID($sessionID, $stubGame);
    }

    public function test_isTestPlayer_isTestPlayer0_false()
    {
        $stubRepo = $this->createStub(PlayerRepository::class);
        $stubRepo->method('getBySessionIDGameID')
            ->willReturn(LoginInfo::factory()->make([
                'sboClientID' => 1,
                'sessionID' => 1,
                'loginIP' => 'loginIP',
                'isTestPlayer' => 0
            ]));

        $stubGame = $this->createStub(IGame::class);

        $player = $this->makePlayer($stubRepo);
        
        $player->initBySessionIDGameID(1, $stubGame);
        $result = $player->isTestPlayer();

        $this->assertFalse($result);
    }

    public function test_isTestPlayer_isTestPlayer1_true()
    {
        $stubRepo = $this->createStub(PlayerRepository::class);
        $stubRepo->method('getBySessionIDGameID')
            ->willReturn(LoginInfo::factory()->make([
                'sboClientID' => 1,
                'sessionID' => 1,
                'loginIP' => 'loginIP',
                'isTestPlayer' => 1
            ]));

        $stubGame = $this->createStub(IGame::class);

        $player = $this->makePlayer($stubRepo);

        $player->initBySessionIDGameID(1, $stubGame);
        $result = $player->isTestPlayer();

        $this->assertTrue($result);
    }
}