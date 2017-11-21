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
    public function index($league, $round)
    {
        $leagues = new league();
        $coed = $leagues->where('league_name', $league)->first();
        $resultsByTier = $coed->round($round)->roundResultsByTier();
       
        $data = array('resultsByTier' => $resultsByTier);
        return view('results', $data);
    }
}
