<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ViewGameTest extends TestCase
{
    /** @test */
    public function the_game_page_shows_correct_info()
    {
        Http::fake([
            'https://api.igdb.com/v4/games' => $this->fakeSingleGame(),
        ]);
        $response = $this->get(route('games.show', 'fake-dead-island-2'));
        $response->assertSuccessful();
        $response->assertSee('Fake Dead Island 2');
        $response->assertSee('Action');
        $response->assertSee('PC');
        $response->assertSee('Animal Crossing: New Horizons');
        $response->assertSee('80%');
    }

    public function fakeSingleGame()
    {
        return Http::response([
            0 => [
                "id" => 12345,
                "cover" => [
                    "id" => 12345,
                    "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                ],
                "first_release_date" => 1234567890,
                "name" => "Fake Dead Island 2",
                "genres" => [
                    0 => [
                        "id" => 12345,
                        "name" => "Action",
                    ],
                    1 => [
                        "id" => 12345,
                        "name" => "Adventure",
                    ],
                    2 => [
                        "id" => 12345,
                        "name" => "RPG",
                    ],
                ],
                "platforms" => [
                    0 => [
                        "id" => 48,
                        "abbreviation" => "PC",
                    ],
                    1 => [
                        "id" => 49,
                        "abbreviation" => "PS4",
                    ],
                    2 => [
                        "id" => 130,
                        "abbreviation" => "XONE",
                    ],
                ],
                "rating" => 80.0,
                "slug" => "fake-dead-island-2",
                "screenshots" => [
                    0 => [
                        "id" => 12345,
                        "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                    ],
                    1 => [
                        "id" => 12345,
                        "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                    ],
                    2 => [
                        "id" => 12345,
                        "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                    ],
                ],
                "summary" => "Dead Island 2 is an upcoming action role-playing survival horror video game developed by Yager Development and published by Deep Silver for Microsoft Windows, PlayStation 4, and Xbox One. It is the sequel to the 2011 video game Dead Island.",
                "total_rating" => 80.0,
                "total_rating_count" => 1,
                "url" => "https://www.igdb.com/games/dead-island-2",
                "videos" => [
                    0 => [
                        "id" => 12345,
                        "name" => "Dead Island 2 - E3 2014 Trailer",
                        "video_id" => "12345",
                    ],
                ],
                "similar_games" => [
                    0 => [
                        "id" => 12345,
                        "cover" => [
                            "id" => 12345,
                            "url" => "//images.igdb.com/igdb/image/upload/t_thumb/co1f1m.jpg",
                        ],
                        "name" => "Animal Crossing: New Horizons",
                        "rating" => 80.0,
                        "slug" => "animal-crossing-new-horizons",
                        "total_rating" => 80.0,
                        "total_rating_count" => 1,
                        "url" => "https://www.igdb.com/games/dead-island-2",
                    ],
                ],
            ],
        ], 200);
    }
}
