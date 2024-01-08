<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PopularGames extends Component
{
    public $popularGames = [];

    public function render()
    {
        return view('livewire.popular-games');
    }

    public function loadPopularGames()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;

        $unformatedPopularGames = Cache::remember('popular-games', 600, function () use ($before, $after){
            return Http::withHeaders(config('services.igdb'))
                ->withBody(
                    "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug;
                where platforms = (48,49,130, 167, 169, 6, 39, 34) & total_rating_count > 5
                & (first_release_date >= {$before} & first_release_date < {$after});
                sort total_rating_count desc;
                limit 12;",
                    "text/plain"
                )->post('https://api.igdb.com/v4/games')
                ->json();
        });

        $this->popularGames = $this->formatForView($unformatedPopularGames);
    }

    private function formatForView($games)
    {
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => isset($game['cover']) ? str_replace('thumb', 'cover_big', $game['cover']['url']) : null,
                'rating' => isset($game['rating']) ? round($game['rating']) : null,
                'platforms' => isset($game['platforms']) ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') : null,
            ]);
        })->toArray();
    }
}
