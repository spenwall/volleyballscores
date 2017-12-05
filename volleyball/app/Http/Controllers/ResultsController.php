<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\team;
use App\games;
use App\rounds;
use App\roundResults;
use App\league;

class ResultsController extends Controller
{
    private $_round;
    private $_league;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the results for current tier
     *
     * @return \Illuminate\Http\Response
     */
    public function index($leagueName, $round)
    {
        $leagues = new league();
        $league = $leagues->where('league_name', $leagueName)->first();
        $resultsByTier = $league->round($round)->roundResultsByTier();
         
        $data = array('resultsByTier' => $resultsByTier);
        return view('results', $data);
    }

    public function calculate($leagueName, $round)
    {
        $leagues = new league();
        $league = $leagues->byName($leagueName);
        $roundResults = $league->round($round)->roundResults;
        foreach ($roundResults as $result) {
            $result->calculateRank();
        }
        foreach ($roundResults as $result) {
            $result->calculateNextRoundResults();
        }

        return redirect()->route('results', [$leagueName,$round]);
    }
}
