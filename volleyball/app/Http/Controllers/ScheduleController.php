<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;
use App\games;
use App\league;

class ScheduleController extends Controller
{
    public function index($leagueName)
    {
        $league = league::byName($leagueName);
        $games = $league->gamesbyTier(1);
        $games = $games->groupBy('rounds_id');
        $tiers = $league->leagueTiers();
        // foreach ($games as $game) {
        //     dd($game->groupBy());
        // }
        // $games = array();
        // foreach($allLocations as $building => $collection) {
        //     $courts[$building] = $collection->groupBy('court');
        // }
        return view('schedule', ['games' => $games, 'tiers' => $tiers]);
    }
}
