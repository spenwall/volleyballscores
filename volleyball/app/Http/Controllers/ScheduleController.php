<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;
use App\games;

class ScheduleController extends Controller
{
    public function index($league)
    {
        $tiers = team::leagueTiers($league);
        $allGames = games::gamesInTierRound(1, 2, $league);
        $allLocations = $allGames->groupBy('location');
        $games = array();
        foreach($allLocations as $building => $collection) {
            $courts[$building] = $collection->groupBy('court');
        }
        return view('schedule', ['tiers' => $tiers, 'courts' => $courts]);
    }
}
