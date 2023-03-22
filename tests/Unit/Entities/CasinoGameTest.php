<?php

use Tests\TestCase;
use App\Models\Game;
use App\Entities\CasinoGame;
use App\Exceptions\Game\GameIDNotFoundException;
use App\Repositories\GameRepository;

class CasinoGameTest extends TestCase
{
    public function makeGame($repo = null)
    {
        $repo ??= $this->createStub(GameRepository::class);

        return new CasinoGame($repo);
    }

    public function test_initByGameID_mockRepo_getByGameID()
    {
        $mockRepo = $this->createMock(GameRepository::class);
        $mockRepo->expects($this->once())
            ->method('getByGameID')
            ->willReturn(Game::factory()->make([
                'gameID' => 1,
                'isTestModeEnabled' => 0,
            ]));

        $game = $this->makeGame($mockRepo);
        $game->initByGameID(1);
    }

    public function test_initByGameID_stubRepoEmptyReturn_GameIDNotFoundException()
    {
        $this->expectException(GameIDNotFoundException::class);

        $stubRepo = $this->createStub(GameRepository::class);
        $stubRepo->method('getByGameID')
            ->willReturn(null);

        $game = $this->makeGame($stubRepo);
        $game->initByGameID(1);
    }

    public function test_isUnderMaintenance_isTestModeEnabled1_true()
    {
        $stubRepo = $this->createStub(GameRepository::class);
        $stubRepo->method('getByGameID')
            ->willReturn(Game::factory()->make([
                'gameID' => 1,
                'isTestModeEnabled' => 1,
            ]));

        $game = $this->makeGame($stubRepo);
        $game->initByGameID(1);

        $result = $game->isUnderMaintenance();

        $this->assertTrue($result);
    }

    public function test_isUnderMaintenance_isTestModeEnabled0_false()
    {
        $stubRepo = $this->createStub(GameRepository::class);
        $stubRepo->method('getByGameID')
            ->willReturn(Game::factory()->make([
                'gameID' => 1,
                'isTestModeEnabled' => 0,
            ]));

        $game = $this->makeGame($stubRepo);
        $game->initByGameID(0);

        $result = $game->isUnderMaintenance();

        $this->assertFalse($result);
    }
}