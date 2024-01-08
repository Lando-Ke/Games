<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ComingSoon extends Component
{
    public $comingSoon = [];

    public function render()
    {
        return view('livewire.coming-soon');
    }

    public function loadComingSoon()
    {
        $current = Carbon::now()->timestamp;

        $unformatedComingSoon = Cache::remember('coming-soon', 600, function () use ($current) {
            return Http::withHeaders(config('services.igdb'))
                ->withBody(
                    "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug;
                where platforms = (48,49,130, 167, 169, 6, 39, 34)
                & (first_release_date > {$current}
                );
                sort total_rating_count desc;
                limit 4;",
                    "text/plain"
                )->post('https://api.igdb.com/v4/games')
                ->json();
        });

        $this->comingSoon = $this->formatForView($unformatedComingSoon);
    }

    private function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'name' => strlen($game['name']) > 25 ? substr($game['name'], 0, 20) . '...' : $game['name'],
                'coverImageUrl' => isset($game['cover']) ? str_replace('thumb', 'cover_small', $game['cover']['url']) : 'https://placehold.co/90x128?text=No+Image+Available',
                'releaseDate' => isset($game['first_release_date']) ? Carbon::parse($game['first_release_date'])->format('M d, Y') : 'TBA',
            ]);
        })->toArray();
    }
}
