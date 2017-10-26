<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\team;
use App\games;
use App\rounds;

class ResultsController extends Controller
{
    private $_round;

    private $_team;
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
        $this->_round = $round;
        $user = Auth::user();
        $name = $user->name;
        $this->_team = $user->team;
        $gamesByTier = $this->_gamesByTier();
        $resultsByTier = $this->_resultsByTier();
        $teamsByTiers = $this->_teamsByTiers();
        $data = array('resultsByTier' => $resultsByTier, 'teamsByTiers' => $teamsByTiers);
        return view('results', $data);
    }

    private function _gamesByTier()
    {
        $league = $this->_team->league;
        $tiers = $this->_team->leagueTiers();
        $roundCollection = rounds::find($this->_round);
        $gamesByTier = array();
        foreach ($tiers as $tier) {
            $gamesByTier[$tier->tier] = $roundCollection->gamesByLeagueTier($league, $tier->tier);
        }

        return $gamesByTier;
    }

    private function _teamsByTiers()
    {
        $tiersInLeague = $this->_team->leagueTiers();
        $teamsInTiers = array();
        foreach ($tiersInLeague as $tier)
        {
            $teamsInTiers[$tier->tier] = $this->_team->teamsForRoundAndTier($this->_round, $tier->tier);
        }
        return $teamsInTiers;
    }

    private function _resultsByTier()
    {
        $teamsByTiers = $this->_teamsByTiers();
        foreach($teamsByTiers as $tier => $teams){
            foreach ($teams as $team1) {
                foreach ($teams as $team2) {
                    if ($team1->rank == $team2->rank) {
                        $results[$tier][$team1->rank][$team2->rank] = '-';
                    } else {
                        $results[$tier][$team1->rank][$team2->rank] = $this->_winner($team1, $team2, $tier);
                    }
                }
            }
        }
        if (isset($results)) {
            return $results;
        } else {
            return false;
        }
    }

    private function _winner($t1, $t2, $tier)
    {
        $winner = games::winner($t1, $t2, $this->_round, $tier);

        return $winner;
    }
}
