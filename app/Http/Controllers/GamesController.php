<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $game = Http::withHeaders(config('services.igdb'))
            ->withBody(
                "
                fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, involved_companies.company.name, genres.name, aggregated_rating, summary, websites.*, videos.*, screenshots.*, similar_games.cover.url, similar_games.name, similar_games.rating, similar_games.platforms.abbreviation, similar_games.slug;
                where slug=\"{$slug}\";
                ", "text/plain"
            )->post('https://api.igdb.com/v4/games')
            ->json();

        abort_if(!$game, 404);

        return view('show', [
            'game' => $this->formatGameForView($game[0]),
        ]);
    }

    private function formatGameForView($game)
    {
        return collect($game)->merge([
            'coverImageUrl' => isset($game['cover']) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : 'https://placehold.co/264x374?text=No+Image+Available',
            'summary' => isset($game['summary']) ? $game['summary']: null,
            'first_release_date' => isset($game['first_release_date']) ? Carbon::parse($game['first_release_date'])->format('M d, Y') : null,
            'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', '),
            'involved_companies' => isset($game['involved_companies']) ? collect($game['involved_companies'])->pluck('company.name')->implode(', ') : null,
            'genres' => isset($game['genres']) ? collect($game['genres'])->pluck('name')->implode(', ') : null,
            'rating' => isset($game['rating']) ? round($game['rating']) : null,
            'aggregated_rating' => isset($game['aggregated_rating']) ? round($game['aggregated_rating']) . '%' : null,
            'trailer' => isset($game['videos']) ? 'https://youtube.com/watch/' . $game['videos'][0]['video_id'] : null,
            'screenshots' => collect($game['screenshots'])->map(function ($screenshot) {
                return [
                    'huge' => Str::replaceFirst('thumb', 'screenshot_huge', $screenshot['url']),
                    'big' => Str::replaceFirst('thumb', 'screenshot_big', $screenshot['url']),
                    'normal' => Str::replaceFirst('thumb', 'screenshot_med', $screenshot['url']),
                    'small' => Str::replaceFirst('thumb', 'screenshot_small', $screenshot['url']),
                ];
            })->take(6),
            'similar_games' => isset($game['similar_games']) ? collect($game['similar_games'])->map(function ($game) {
                return collect($game)->merge([
                    'coverImageUrl' => isset($game['cover']) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : null,
                    'rating' => isset($game['rating']) ? round($game['rating']) : null,
                    'platforms' => isset($game['platforms']) ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') : null,
                ]);
            })->take(6) : [],
            'social' => [
                'website' => isset($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    return $website['category'] == 1;
                })->first() : null,
                'facebook' => isset($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'facebook');
                })->first() : null,
                'twitter' => isset($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'twitter');
                })->first() : null,
                'instagram' => isset($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'instagram');
                })->first() : null,
                'twitch' => isset($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    return Str::contains($website['url'], 'twitch');
                })->first() : null,
            ],
        ])->toArray();
    }

}
