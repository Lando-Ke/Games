<?php

namespace Tests\Feature;

use App\Http\Livewire\PopularGames;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class PopularGamesTest extends TestCase
{
    /** @test */

    public function the_main_page_shows_popular_games()
    {
        Http::fake([
            'https://api.igdb.com/v4/games' => $this->fakePopularGames(),
        ]);

        Livewire::test(PopularGames::class)
            ->assertSet('popularGames', [])
            ->call('loadPopularGames')
            ->assertSee('Fake Game 1')
            ->assertSee('Fake Game 2')
            ->assertSee('Fake Game 3')
            ->assertSee('Fake Game 4');
    }

    public function fakePopularGames()
    {
        return Http::response([
            0 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Game 1",
                "slug" => "fake-game-1",
                "rating" => 80.0,
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                ],
            ],
            1 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Game 2",
                "slug" => "fake-game-2",
                "rating" => 80.0,
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                ],
            ],
            2 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Game 3",
                "slug" => "fake-game-3",
                "rating" => 80.0,
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                ],
            ],
            3 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Game 4",
                "slug" => "fake-game-4",
                "rating" => 80.0,
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                ],
            ],
            4 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Game 5",
                "slug" => "fake-game-5",
                "rating" => 80.0,
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                ],
            ],
            5 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Game 6",
                "slug" => "fake-game-6",
                "rating" => 80.0,
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                ],
            ],
        ], 200);
    }
}
