<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\team;
use App\games;
use App\rounds;

class ResultsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the results for current tier
     *
     * @return \Illuminate\Http\Response
     */
    public function index($round)
    {
        $user = Auth::user();
        $name = $user->name;
        $team = $user->team;
        $gamesByTier = $this->_gamesByTier($team, $round);
        $data = array('$team' => $team, 'gamesByTier' => $gamesByTier);
        return view('results', $data);
    }

    private function _gamesByTier($team, $round)
    {
        $league = $team->league;
        $tiers = $team->leagueTiers();
        $roundCollection = rounds::find($round);
        $gamesByTier = array();
        foreach ($tiers as $tier) {
            $gamesByTier[$tier->tier] = $roundCollection->gamesByLeagueTier($league, $tier->tier);
        }

        return $gamesByTier;
    }
}
