<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\team;
use App\games;
use App\League;

class ScheduleController extends Controller
{
    /**
     * Schedule Controller
     * 
     */
    public function index($leagueName)
    {
        $league = League::byName($leagueName);
        $Games = $league->gamesbyTier(1);
        $round_games = $games->groupBy('rounds_id');
        $tiers = $league->leagueTiers();
        foreach ($round_games as $game) {
            //seperate the games into days then into locations then into courts
        }
        return view('schedule', ['games' => $round_games, 'tiers' => $tiers]);
    }
}
