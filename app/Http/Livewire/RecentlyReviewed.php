<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    public function render()
    {
        return view('livewire.recently-reviewed');
    }

    public function loadRecentlyReviewed()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $current = Carbon::now()->timestamp;

        $unformattedRecentlyReviewed = Cache::remember('recently-reviewed', 600, function () use ($before, $current){
            return Http::withHeaders(config('services.igdb'))
            ->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug, summary;
            where platforms = (48,49,130, 167, 169, 6, 39, 34)
            & (first_release_date >= {$before} 
            & first_release_date < {$current}
            & rating_count > 5);
            sort total_rating_count desc;
            limit 3;",
                "text/plain"
            )->post('https://api.igdb.com/v4/games')
            ->json();
        });

        $this->recentlyReviewed = $this->formatForView($unformattedRecentlyReviewed);
    }

    private function formatForView($games)
    {
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => isset($game['cover']) ? str_replace('thumb', 'cover_big', $game['cover']['url']) : null,
                'rating' => isset($game['rating']) ? round($game['rating']).'%' : null,
                'platforms' => isset($game['platforms']) ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') : null,
            ]);
        })->toArray();
    }
         
}
