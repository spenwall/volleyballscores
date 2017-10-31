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
        $this->_round = $round;
        $this->_league = $league;
        $gamesByTier = $this->_gamesByTier();
        $resultsByTier = $this->_resultsByTier();
        $teamsByTiers = team::teamsByTiers($league, $round);
        $data = array('resultsByTier' => $resultsByTier, 'teamsByTiers' => $teamsByTiers);
        return view('results', $data);
    }

    private function _gamesByTier()
    {
        $league = $this->_league;
        $tiers = team::leagueTiers($this->_league);
        $roundCollection = rounds::find($this->_round);
        $gamesByTier = array();
        foreach ($tiers as $tier) {
            $gamesByTier[$tier->tier] = $roundCollection->gamesByLeagueTier($league, $tier->tier);
        }
        return $gamesByTier;
    }

    private function _teamsByTiers()
    {
        $tiersInLeague = team::leagueTiers($this->_league);
        $teamsInTiers = array();
        foreach ($tiersInLeague as $tier)
        {
            $teamsInTiers[$tier->tier] = team::teamsForRoundAndTier($this->_round, $tier->tier, $this->_league);
        }
        
        return $teamsInTiers;
    }

    private function _resultsByTier()
    {
        $teamsByTiers = team::teamsByTiers($this->_league, $this->_round);
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
