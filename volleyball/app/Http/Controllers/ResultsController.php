<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\team;

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
    public function index()
    {
        $user = Auth::user();
        $name = $user->name;
        $team = $user->team;
        $teams = $team->allLeagueTierTeams();
        $teamNames = $this->_teamNames($teams);
        $data = array('teamNames' => $teamNames);
        return view('results', $data);
    }

    private function _teamNames($teams)
    {
        $teamNames = array();
        foreach ($teams as $team) {
            $teamNames[$team->rank] = $team->team_name;
        }

        return $teamNames;
    }
}
