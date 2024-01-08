<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    public function render()
    {
        return view('livewire.most-anticipated');
    }

    public function loadMostAnticipated()
    {
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;
        $current = Carbon::now()->timestamp;

        $unformattedMostAnticipated = Cache::remember('most-anticipated', 600, function () use ($afterFourMonths, $current) {
            return Http::withHeaders(config('services.igdb'))
                ->withBody(
                    "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug;
                where platforms = (48,49,130, 167, 169, 6, 39, 34)
                & (first_release_date >= {$current} 
                & first_release_date < {$afterFourMonths});
                sort total_rating_count desc;
                limit 4;",
                    "text/plain"
                )->post('https://api.igdb.com/v4/games')
                ->json();
        });

        $this->mostAnticipated = $this->formatForView($unformattedMostAnticipated);
    }

    private function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'name' => strlen($game['name']) > 25 ? substr($game['name'], 0, 20) . '...' : $game['name'],
                'coverImageUrl' => isset($game['cover']) ? str_replace('thumb', 'cover_small', $game['cover']['url']) : 'https://placehold.co/90x128?text=No+Image+Available',
                'releaseDate' => Carbon::parse($game['first_release_date'])->format('M d, Y'),
            ]);
        })->toArray();
    }
}
